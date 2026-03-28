import pandas as pd
import pymysql

conn = pymysql.connect(
    host="127.0.0.1",
    user="root",
    password="secret",
    database="pspp",
    charset="utf8mb4"
)

cursor = conn.cursor()

def parse_subject_code(code):
    code = str(code).strip()

    if len(code) < 6:
        return None

    try:
        sequence = int(code[4:6])
    except:
        return None

    semester = 1 if code[-1] in ['1','3','5','7','9'] else 2

    return {
        "group_code": code[0],
        "level": int(code[1]),
        "year": int(code[2]),
        "type": "พื้นฐาน" if code[3] == "1" else "เพิ่มเติม",
        "semester": semester
    }

group_map = {
    "ท": 1,
    "ค": 2,
    "ว": 3,
    "ส": 4,
    "พ": 5,
    "ศ": 6,
    "ง": 7,
    "อ": 8,
    "บ": 9
}

df = pd.read_excel("subjects.xlsx")

count = 0

for _, row in df.iterrows():
    code = str(row['subject_code']).strip()
    print(code)  # 👈 ใส่ตรงนี้
    
    name = str(row['subject_name']).strip()

    parsed = parse_subject_code(code)

    if not parsed:
        print(f"❌ format ผิด: {code}")
        continue

    if parsed['group_code'] not in group_map:
        print(f"❌ ไม่พบกลุ่ม: {code}")
        continue

    group_id = group_map[parsed['group_code']]

    sql = """
    INSERT INTO subjects 
    (subject_code, subject_name, group_id, level, year, semester, subject_type)
    VALUES (%s, %s, %s, %s, %s, %s, %s)
    ON DUPLICATE KEY UPDATE subject_name = VALUES(subject_name)
    """

    values = (
        code,
        name,
        group_id,
        parsed['level'],
        parsed['year'],
        parsed['semester'],
        parsed['type']
    )

    try:
        cursor.execute(sql, values)
        count += 1
    except Exception as e:
        print(f"❌ Error: {code} → {e}")

conn.commit()

print(f"✅ Insert สำเร็จ {count} รายการ")

cursor.close()
conn.close()