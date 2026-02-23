
import mysql.connector

db_config = {
    'host': '92.249.63.61',
    'database': 'kesictrs_test',
    'user': 'kesictrs_admin',
    'password': 'Atik3777??'
}

def execute_sql_file(filepath):
    try:
        conn = mysql.connector.connect(**db_config)
        if conn.is_connected():
            cursor = conn.cursor()
            print(f"Veritabanı bağlantısı başarılı. SQL dosyasını çalıştırılıyor: {filepath}")
            
            with open(filepath, 'r') as f:
                sql_file_content = f.read()
            
            # SQL komutlarını noktalı virgüle göre ayırın
            sql_commands = [cmd.strip() for cmd in sql_file_content.split(';') if cmd.strip()]
            
            for command in sql_commands:
                try:
                    cursor.execute(command)
                    print(f"Komut başarıyla yürütüldü: {command[:70]}...")
                except mysql.connector.Error as err:
                    print(f"Komut yürütülürken hata oluştu: {err}\nKomut: {command[:70]}...")
            
            conn.commit()
            print("Tüm SQL komutları tamamlandı ve değişiklikler kaydedildi.")

    except mysql.connector.Error as err:
        print(f"Veritabanı hatası: {err}")

    finally:
        if 'conn' in locals() and conn.is_connected():
            cursor.close()
            conn.close()
            print("Veritabanı bağlantısı kapatıldı.")

# SQL dosyasını çalıştır
execute_sql_file('tonmeister-portfolio/database.sql')
