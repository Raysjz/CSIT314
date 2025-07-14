from faker import Faker
import random
from datetime import datetime, timedelta

fake = Faker()

# --- Date Range for Last 30 Days ---
now = datetime.now()
one_month_ago = now - timedelta(days=30)

# --- 1. User Profiles ---
profile_names = ['User Admin', 'Homeowner', 'Cleaner', 'Platform Management']
profile_inserts = []
for name in profile_names:
    is_suspended = 'FALSE'
    profile_inserts.append(
        f"INSERT INTO user_profiles (profile_name, is_suspended) VALUES ('{name}', {is_suspended});"
    )

# --- 2. Service Categories ---
categories = [
    'Home Cleaning', 'Deep Cleaning', 'Carpet Cleaning', 'Window Cleaning',
    'Office Cleaning', 'Move-in/Move-out Cleaning', 'Post-Construction Cleaning',
    'Laundry Services', 'Pressure Washing'
]
category_inserts = []
for cat in categories:
    is_suspended = random.choices(['TRUE', 'FALSE'], weights=[1, 9], k=1)[0]
    category_inserts.append(
        f"INSERT INTO service_categories (category_name, is_suspended) VALUES ('{cat}', {is_suspended});"
    )
category_ids = list(range(1, len(categories) + 1))

# --- 3. User Accounts ---
# Add these before generating random accounts
account_inserts = [
    "INSERT INTO user_accounts (ua_username, ua_password, full_name, email, profile_name, profile_id, is_suspended) VALUES ('admin', '1234', 'Admin User', 'admin@example.com', 'User Admin', 1, FALSE);",
    "INSERT INTO user_accounts (ua_username, ua_password, full_name, email, profile_name, profile_id, is_suspended) VALUES ('homeowner', '1234', 'Homeowner User', 'homeowner@example.com', 'Homeowner', 2, FALSE);",
    "INSERT INTO user_accounts (ua_username, ua_password, full_name, email, profile_name, profile_id, is_suspended) VALUES ('cleaner', '1234', 'Cleaner User', 'cleaner@example.com', 'Cleaner', 3, FALSE);",
    "INSERT INTO user_accounts (ua_username, ua_password, full_name, email, profile_name, profile_id, is_suspended) VALUES ('platformmgmt', '1234', 'Platform Mgmt User', 'platformmgmt@example.com', 'Platform Management', 4, FALSE);"
]

homeowner_account_ids = [2]  # IDs for the fixed users
cleaner_account_ids = [3]
account_id_to_profile = {1: 'User Admin', 2: 'Homeowner', 3: 'Cleaner', 4: 'Platform Management'}
account_id = 5  # Start after the four fixed users

for _ in range(100):
    username = fake.unique.user_name()
    password = fake.password()
    full_name = fake.name().replace("'", "''")
    email = fake.unique.email()
    profile_idx = random.randint(0, len(profile_names) - 1)
    profile_id = profile_idx + 1  # 1-based index
    profile_name = profile_names[profile_idx]
    is_suspended = random.choices(['TRUE', 'FALSE'], weights=[1, 9], k=1)[0]
    account_inserts.append(
        f"INSERT INTO user_accounts (ua_username, ua_password, full_name, email, profile_name, profile_id, is_suspended) "
        f"VALUES ('{username}', '{password}', '{full_name}', '{email}', '{profile_name}', {profile_id}, {is_suspended});"
    )
    account_id_to_profile[account_id] = profile_name
    if profile_name == 'Homeowner':
        homeowner_account_ids.append(account_id)
    if profile_name == 'Cleaner':
        cleaner_account_ids.append(account_id)
    account_id += 1

# --- 4. Cleaner Services ---
service_titles = [
    'Standard Home Cleaning', 'Deep Spring Cleaning', 'Carpet Refresh', 'Crystal Clear Windows',
    'Office Shine', 'Move-in/Move-out Package', 'Post-Construction Cleanup', 'Laundry Pickup & Delivery',
    'Upholstery Cleaning', 'Driveway Pressure Washing', 'Quick Home Clean', 'Window Shine',
    'Deep Clean Deluxe', 'Move-Out Special', 'Carpet Rescue', 'Post-Reno Cleanup'
]
service_descriptions = [
    'Thorough cleaning of all rooms, dusting, vacuuming, and mopping.',
    'Intensive cleaning including appliances, baseboards, and hard-to-reach areas.',
    'Professional carpet shampooing and stain removal for up to 3 rooms.',
    'Interior and exterior window cleaning for homes up to 2 stories.',
    'Regular office cleaning including workspaces, restrooms, and common areas.',
    'Complete cleaning for moving in or out, including inside cabinets and appliances.',
    'Removal of dust and debris after renovation or construction work.',
    'Wash, dry, fold, and delivery service for weekly laundry.',
    'Steam cleaning for sofas, chairs, and other furniture.',
    'High-pressure cleaning for driveways, patios, and exterior walls.',
    'Fast and efficient cleaning for apartments.',
    'Professional window cleaning for homes.',
    'Comprehensive deep cleaning for large homes.',
    'Move-out cleaning with attention to detail.',
    'Stain removal and deep carpet cleaning.',
    'Cleaning after construction or renovation.'
]
availabilities = [
    'Mon-Fri 9am-5pm', 'Weekends 10am-4pm', 'Mon, Wed, Fri 1pm-6pm', 'Sat-Sun 8am-12pm',
    'Mon-Fri 6pm-10pm', 'Flexible', 'By appointment', 'Tue, Thu 10am-2pm',
    'Fri 2pm-6pm', 'Sat 9am-3pm', 'Mon-Fri 8am-12pm', 'Sat 10am-2pm', 'Weekends 9am-5pm'
]

cleaner_service_inserts = []
service_id_to_cleaner_and_category = dict()
service_id_list = []
service_id = 1
for acc_id in cleaner_account_ids:
    for _ in range(random.randint(1, 3)):
        category_id = random.choice(category_ids)
        title = random.choice(service_titles)
        description = random.choice(service_descriptions)
        price = round(random.uniform(40, 250), 2)
        availability = random.choice(availabilities)
        is_suspended = random.choices(['TRUE', 'FALSE'], weights=[1, 9], k=1)[0]
        created_at = fake.date_time_between(start_date=one_month_ago, end_date=now)
        updated_at = created_at
        cleaner_service_inserts.append(
            f"INSERT INTO cleaner_services (cleaner_account_id, category_id, title, description, price, availability, is_suspended, created_at, updated_at) "
            f"VALUES ({acc_id}, {category_id}, '{title}', '{description}', {price}, '{availability}', {is_suspended}, '{created_at}', '{updated_at}');"
        )
        service_id_to_cleaner_and_category[service_id] = (acc_id, category_id)
        service_id_list.append(service_id)
        service_id += 1

# --- 5. Service Views ---
service_views_inserts = []
all_pairs = [(s, v) for s in service_id_list for v in homeowner_account_ids]
random.shuffle(all_pairs)
unique_views = all_pairs[:100]
for service_id_val, viewer_account_id in unique_views:
    viewed_at = fake.date_time_between(start_date=one_month_ago, end_date=now)
    service_views_inserts.append(
        f"INSERT INTO service_views (service_id, viewed_at, viewer_account_id) "
        f"VALUES ({service_id_val}, '{viewed_at}', {viewer_account_id});"
    )

# --- 6. Service Shortlists (unique per homeowner/service pair) ---
service_shortlists_inserts = []
unique_shortlists = set()
attempts = 0
while len(service_shortlists_inserts) < 100 and attempts < 1000:
    homeowner_id = random.choice(homeowner_account_ids)
    service_id_val = random.choice(service_id_list)
    pair = (homeowner_id, service_id_val)
    if pair not in unique_shortlists:
        unique_shortlists.add(pair)
        shortlisted_at = fake.date_time_between(start_date=one_month_ago, end_date=now)
        is_confirmed = random.choices(['TRUE', 'FALSE'], weights=[3, 7], k=1)[0]
        is_deleted = random.choices(['TRUE', 'FALSE'], weights=[1, 9], k=1)[0]
        service_shortlists_inserts.append(
            f"INSERT INTO service_shortlists (homeowner_account_id, service_id, shortlisted_at, is_confirmed, is_deleted) "
            f"VALUES ({homeowner_id}, {service_id_val}, '{shortlisted_at}', {is_confirmed}, {is_deleted});"
        )
    attempts += 1

# --- 7. Matching Bookings ---
matching_bookings_inserts = []
for _ in range(100):
    homeowner_id = random.choice(homeowner_account_ids)
    service_id_val = random.choice(list(service_id_to_cleaner_and_category.keys()))
    cleaner_id, category_id = service_id_to_cleaner_and_category[service_id_val]
    booking_date = fake.date_between(start_date=one_month_ago, end_date=now)
    status = random.choice(['confirmed', 'completed', 'cancelled'])
    is_deleted = 'FALSE'
    matching_bookings_inserts.append(
        f"INSERT INTO matching_bookings (homeowner_account_id, cleaner_account_id, service_id, category_id, booking_date, status, is_deleted) "
        f"VALUES ({homeowner_id}, {cleaner_id}, {service_id_val}, {category_id}, '{booking_date}', '{status}', {is_deleted});"
    )

# --- Output to SQL file ---
all_sql = (
    profile_inserts
    + category_inserts
    + account_inserts
    + cleaner_service_inserts
    + service_views_inserts
    + service_shortlists_inserts
    + matching_bookings_inserts
)

with open("sample_data.sql", "w", encoding="utf-8") as f:
    for stmt in all_sql:
        f.write(stmt + "\n")

print("Sample data SQL file generated: sample_data.sql")
