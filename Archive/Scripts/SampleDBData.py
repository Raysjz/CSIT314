import psycopg2
from dotenv import load_dotenv
import os
from faker import Faker
import random

# Load environment variables
load_dotenv()

USER = os.getenv("user")
PASSWORD = os.getenv("password")
HOST = os.getenv("host")
PORT = os.getenv("port")
DBNAME = os.getenv("dbname")

fake = Faker()

try:
    connection = psycopg2.connect(
        user=USER,
        password=PASSWORD,
        host=HOST,
        port=PORT,
        dbname=DBNAME
    )
    print("Connection successful!")

    cursor = connection.cursor()

    # Insert user_profiles
    profile_names = ['Admin', 'Homeowner', 'Cleaner', 'Platform Management']
    profile_ids = []
    for name in profile_names:
        is_suspended = fake.boolean(chance_of_getting_true=10)
        cursor.execute(
            "INSERT INTO user_profiles (profile_name, is_suspended) VALUES (%s, %s) RETURNING profile_id",
            (name, is_suspended)
        )
        profile_ids.append(cursor.fetchone()[0])

    # Insert service_categories
    categories = [
        'Home Cleaning', 'Deep Cleaning', 'Carpet Cleaning', 'Window Cleaning',
        'Office Cleaning', 'Move-in/Move-out Cleaning', 'Post-Construction Cleaning',
        'Laundry Services', 'Pressure Washing'
    ]
    category_ids = []
    for cat in categories:
        is_suspended = fake.boolean(chance_of_getting_true=5)
        cursor.execute(
            "INSERT INTO service_categories (category_name, is_suspended) VALUES (%s, %s) RETURNING category_id",
            (cat, is_suspended)
        )
        category_ids.append(cursor.fetchone()[0])

    # Insert user_accounts (all profiles)
    account_ids = []
    cleaner_account_ids = []
    homeowner_account_ids = []
    for _ in range(100):
        profile_idx = random.randint(0, len(profile_ids)-1)
        profile_id = profile_ids[profile_idx]
        profile_name = profile_names[profile_idx]
        username = fake.unique.user_name()
        password = fake.password()
        full_name = fake.name()
        email = fake.unique.email()
        is_suspended = fake.boolean(chance_of_getting_true=10)
        cursor.execute(
            """
            INSERT INTO user_accounts
            (ua_username, ua_password, full_name, email, profile_name, profile_id, is_suspended)
            VALUES (%s, %s, %s, %s, %s, %s, %s)
            RETURNING account_id
            """,
            (username, password, full_name, email, profile_name, profile_id, is_suspended)
        )
        acc_id = cursor.fetchone()[0]
        account_ids.append(acc_id)
        if profile_name == 'Cleaner':
            cleaner_account_ids.append(acc_id)
        if profile_name == 'Homeowner':
            homeowner_account_ids.append(acc_id)

    # Insert cleaner_services
    for cleaner_id in cleaner_account_ids:
        for _ in range(random.randint(1, 3)):
            category_id = random.choice(category_ids)
            title = fake.sentence(nb_words=4)
            description = fake.text(max_nb_chars=200)
            price = round(random.uniform(20, 100), 2)
            availability = "Weekdays 9am-5pm"
            is_suspended = fake.boolean(chance_of_getting_true=5)
            cursor.execute(
                """
                INSERT INTO cleaner_services
                (cleaner_account_id, category_id, title, description, price, availability, is_suspended)
                VALUES (%s, %s, %s, %s, %s, %s, %s)
                """,
                (cleaner_id, category_id, title, description, price, availability, is_suspended)
            )

    # Fetch all service_ids from cleaner_services
    cursor.execute("SELECT service_id FROM cleaner_services")
    service_ids = [row[0] for row in cursor.fetchall()]

    # Generate unique service_views
    all_pairs = [(s, v) for s in service_ids for v in homeowner_account_ids]
    random.shuffle(all_pairs)
    unique_views = all_pairs[:100]  # Adjust as needed

    for service_id, viewer_account_id in unique_views:
        viewed_at = fake.date_time_this_year()
        cursor.execute(
            """
            INSERT INTO service_views (service_id, viewed_at, viewer_account_id)
            VALUES (%s, %s, %s)
            """,
            (service_id, viewed_at, viewer_account_id)
        )

    # Generate service_shortlists
    for _ in range(100):
        homeowner_id = random.choice(homeowner_account_ids)
        service_id = random.choice(service_ids)
        shortlisted_at = fake.date_time_this_year()
        is_confirmed = fake.boolean(chance_of_getting_true=30)
        is_deleted = fake.boolean(chance_of_getting_true=10)
        cursor.execute(
            """
            INSERT INTO service_shortlists
            (homeowner_account_id, service_id, shortlisted_at, is_confirmed, is_deleted)
            VALUES (%s, %s, %s, %s, %s)
            """,
            (homeowner_id, service_id, shortlisted_at, is_confirmed, is_deleted)
        )

    # Commit all changes
    connection.commit()
    cursor.close()
    connection.close()
    print("Sample data generation complete and connection closed.")

except Exception as e:
    print(f"Failed to connect or execute: {e}")
