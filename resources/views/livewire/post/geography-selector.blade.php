<div>

    <div class="col-md-6">
        <label for="geography" class="form-label">Geography</label>
        <select id="geography" class="form-select " name="geography">
            <option selected="" disabled="">Choose...</option>
            <option value="5">Region
            </option>
            <option value="6">City
            </option>
            <option value="7">Country
            </option>
            @foreach ($geographyOptions as $geo)
                sc
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="c2rselect" class="form-label">Select</label>
        <select id="c2rselect" class="form-select " name="c2rselect">

        </select>
    </div>


</div>
