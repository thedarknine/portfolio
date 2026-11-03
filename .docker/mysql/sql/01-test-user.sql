-- Test user for the test environment (.env.test). Run by MariaDB on first start (empty data directory).
-- Doctrine adds the "_test" suffix (config/packages/doctrine.yaml), hence the wildcard.
CREATE USER IF NOT EXISTS 'test'@'%' IDENTIFIED BY 'test';
GRANT ALL PRIVILEGES ON `portfolio\_integration%`.* TO 'test'@'%';
FLUSH PRIVILEGES;
