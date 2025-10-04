<?php

namespace App\Livewire\Marketing;

use App\Jobs\SendWhatsAppMessage;
use App\Models\Chitti;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;

class HitBox extends Component
{
    public $cities = [], $cityIds = [], $city = [];
    public $groups, $group,$userGroup=[2,4];
    public  $user,$customUsers;
    public $message;
    public $templates, $template, $templateId=1;
    public $contacts=[];
    public $channel=[1],$channels=[];
    public $showPost = false;
    public $content = '';
    public $posts = [],$postId;

    public function mount()
    {
        $this->cities = DB::connection('yp')->table('cities')->get();
        $this->channels=['1'=>'WhatsApp','2'=>'Email','3'=>'SMS'];
        $this->templates = $this->getTemplates();
        $this->setTemplates();
        $this->posts = $this->getDailyPost();
        $dd=Chitti::select('chittiId','Title','SubTitle')->orderBydesc('created_at')->limit(10)->get();

    }

    public function cityUpdate()
    {        $this->city= DB::connection('yp')->table('cities')->whereIn('id',$this->cityIds)->get();
        $this->setTemplates();
    }

    public function handaleCustomUsers()
    {
        // $data = array_filter(array_map('trim', explode(',', $this->customUsers)), function($user) {
        //     if (!preg_match('/^\d{10}$/', $user)) {
        //         $this->addError('customUsers', 'Each mobile number must be 10 digit number');
        //         return false;
        //     }
        //     return true;
        // });

        // $this->contacts = array_merge($this->contacts,$data);
    }


    public function render()
    {
        return view('livewire.marketing.hit-box')->layout('components.layouts.admin.base');
    }

    public function sendMessage()
    {

        $this->contacts = $this->getContacts();
        SendWhatsAppMessage::dispatch('917619876249','hello_world')->onQueue('whatsapp');

        // foreach ($this->contacts as $user) {
        //     $phone="91".ltrim($user->phone, '0');
        //     $this->content = str_replace('{user}', $user->name, $this->content);
        //     $this->content = str_replace('{city_name}', collect($this->city)->pluck('name')->implode(', '), $this->content);
        //     SendWhatsAppMessage::dispatch($phone, $this->content)->onQueue('whatsapp');
        // }

        session()->flash('success', 'Message sent successfully!');
    }

    private function getContacts()
    {
        $contacts=[];
        if ($this->city) {
            $contacts = DB::connection('yp')->table('users')->whereIn('city_id', collect($this->city)->pluck('id'))->whereIn('role',$this->userGroup)->get()->toArray();
        }
        return $contacts;

    }

    public function getTemplates()
    {

        return [
            ['id' => 1,'name'=>'Daily Post','format'=>"नमस्ते {user},पढ़े आज का लेख़:"],
            ['id' => 2,'name'=>'Template 2','format'=>'Dear {name},\n Need To Update It Welcome to our site-2'],
            ['id' => 3,'name'=>'Template 3','format'=>'Dear {name},\n Need To Update It Welcome to our site-3'],
            ['id' => 4,'name'=>'Template 3','format'=>'Dear {name},\n Need To Update It Welcome to our site-3'],
        ];

    }
    public function setTemplates()
    {
        if($this->templateId==1){
            $this->showPost = true;
        }else{
            $this->showPost = false;
        }
        $this->template = collect($this->templates)->where('id', $this->templateId)->first()['format'];
        if($this->city){
            $this->template = str_replace('{city_name}', collect($this->city)->pluck('name')->implode(','), $this->template);
        }
        $this->content = $this->template;
        if($this->postId && $this->showPost){
            $post=Chitti::find($this->postId);
            $this->content.= "\n\n https://www.prarang.in/rampur-chitti/".Str::slug($post->SubTitle);
            // $this->template = str_replace('{name}', $this->user, $this->template);
        }
    }

    private function getDailyPost()
    {
        return Chitti::select('chittiId','Title','SubTitle')->orderBydesc('created_at')->limit(10)->get();
    }

}
