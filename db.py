import pymysql

# Database connection details
HOSTNAME = "localhost"
USERNAME = "vivek"
PASSWORD = "phpmyadmin"
DATABASENAME = "finaltp"

# Batch size
BATCH_SIZE = 10

try:
    # Connect to the database
    connection = pymysql.connect(
        host=HOSTNAME,
        user=USERNAME,
        password=PASSWORD,
        database=DATABASENAME,
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor
    )
    print("Database connection successful!")

    with connection.cursor() as cursor:
        # Step 1: Add a temporary column
        cursor.execute("""
            ALTER TABLE `chitti`
            ADD COLUMN `TempTitle` VARCHAR(1500)
            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL;
        """)
        connection.commit()
        print("Temporary column added.")

        # Step 2: Process records in batches
        last_id = 0
        while True:
            # Update a batch of records
            update_query = """
                UPDATE `chitti`
                SET `TempTitle` = `Title`
                WHERE `chittiId` > %s
                ORDER BY `chittiId` ASC
                LIMIT %s;
            """
            cursor.execute(update_query, (last_id, BATCH_SIZE))
            connection.commit()

            # Get the last processed ID
            cursor.execute("SELECT MAX(`chittiId`) AS max_id FROM `chitti` WHERE `TempTitle` IS NOT NULL;")
            result = cursor.fetchone()
            if result['max_id'] is None:
                break
            last_id = result['max_id']

            # Check if there are more records to process
            cursor.execute("SELECT COUNT(1) AS remaining FROM `chitti` WHERE `chittiId` > %s;", (last_id,))
            if cursor.fetchone()['remaining'] == 0:
                break

        print("Batch update complete.")

        # Step 3: Modify the original column
        cursor.execute("""
            ALTER TABLE `chitti`
            CHANGE `Title` `Title` VARCHAR(1500)
            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL;
        """)
        connection.commit()
        print("Original column modified.")

        # Step 4: Copy data back to the original column
        cursor.execute("UPDATE `chitti` SET `Title` = `TempTitle`;")
        connection.commit()
        print("Data copied back to the original column.")

        # Step 5: Drop the temporary column
        # cursor.execute("ALTER TABLE `chitti` DROP COLUMN `TempTitle`;")
        # connection.commit()
        # print("Temporary column dropped.")

except pymysql.MySQLError as e:
    print(f"Error: {e}")
finally:
    if connection:
        connection.close()
        print("Database connection closed.")
