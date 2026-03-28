import pandas as pd

# 🔹 แยกรหัสวิชา
def parse_subject_code(code):
    code = str(code).strip()

    sequence = int(code[4:6])

    return {
        "group_code": code[0],                      # ท ค ว
        "level": int(code[1]),                     # 2 = ม.ต้น, 3 = ม.ปลาย
        "year": int(code[2]),                      # 1,2,3
        "type": "พื้นฐาน" if code[3] == "1" else "เพิ่มเติม",
        "semester": 1 if sequence % 2 == 1 else 2  # คำนวณเทอม
    }

# 🔹 mapping กลุ่มสาระ → group_id (ต้องตรงกับ DB)
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

# 🔹 อ่านไฟล์ Excel
df = pd.read_excel("subjects.xlsx")

sql_list = []

# 🔹 วนลูปทีละแถว
for _, row in df.iterrows():
    code = str(row['subject_code']).strip()
    name = str(row['subject_name']).strip()

    # 🔹 ตรวจข้อมูลเบื้องต้น
    if len(code) != 6:
        print(f"❌ รหัสผิด: {code}")
        continue

    # 🔹 แยกรหัส
    parsed = parse_subject_code(code)

    # 🔹 หา group_id
    if parsed['group_code'] not in group_map:
        print(f"❌ ไม่พบกลุ่มสาระ: {code}")
        continue

    group_id = group_map[parsed['group_code']]

    # 🔹 สร้าง SQL
    sql = f"""INSERT INTO subjects 
(subject_code, subject_name, group_id, level, year, semester, subject_type)
VALUES
('{code}', '{name}', {group_id}, {parsed['level']}, {parsed['year']}, {parsed['semester']}, '{parsed['type']}');"""

    sql_list.append(sql)

# 🔹 บันทึกไฟล์ SQL
with open("subjects.sql", "w", encoding="utf-8") as f:
    f.write("\n".join(sql_list))

print("✅ สร้าง subjects.sql สำเร็จแล้ว")