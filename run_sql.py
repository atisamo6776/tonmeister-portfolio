
import mysql.connector
import os

# Veritabanı kimlik bilgilerini ortam değişkenlerinden almaya çalışın.
# Veya manuel olarak doldurun. Bu dosya, hassas veriler içermemeli ve genellikle bir kerelik kullanılır.

db_config = {
    'host': os.getenv('DB_HOST') or 'localhost',
    'database': os.getenv('DB_NAME') or 'your_database',
    'user': os.getenv('DB_USER') or 'your_user',
    'password': os.getenv('DB_PASSWORD') or 'your_password'
}

def execute_sql_file(filepath):
    try:
        conn = mysql.connector.connect(**db_config)
        if conn.is_connected():
            cursor = conn.cursor()
            print(f"Veritabanı bağlantısı başarılı. SQL dosyasını çalıştırılıyor: {filepath}")
            
            with open(filepath, 'r') as f:
                sql_file_content = f.read()
            
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
execute_sql_file('database.sql')
