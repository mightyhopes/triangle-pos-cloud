-- Cek apakah mata uang Rupiah sudah ada
INSERT INTO currencies (currency_name, code, symbol, thousand_separator, decimal_separator, created_at, updated_at)
SELECT 'Rupiah', 'IDR', 'Rp', '.', ',', NOW(), NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM currencies WHERE code = 'IDR'
);

-- Dapatkan ID mata uang rupiah
SET @rupiah_id = (SELECT id FROM currencies WHERE code = 'IDR' LIMIT 1);

-- Update pengaturan default ke Rupiah jika ID didapatkan
UPDATE settings 
SET default_currency_id = @rupiah_id, 
    default_currency_position = 'prefix'
WHERE @rupiah_id IS NOT NULL; 