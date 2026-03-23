USE association_platform;
ALTER TABLE associations ADD COLUMN thank_you_message TEXT DEFAULT NULL;
ALTER TABLE donations ADD COLUMN siege_id INT DEFAULT NULL AFTER association_id;
ALTER TABLE donations ADD CONSTRAINT fk_donation_siege FOREIGN KEY (siege_id) REFERENCES sieges(id) ON DELETE SET NULL;
