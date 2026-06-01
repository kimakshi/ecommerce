CREATE DATABASE IF NOT EXISTS makhana_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE makhana_shop;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(20) NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  name VARCHAR(190) NOT NULL,
  slug VARCHAR(210) NOT NULL UNIQUE,
  short_desc VARCHAR(255) NULL,
  description TEXT NULL,
  price_mrp DECIMAL(10,2) NOT NULL,
  price_sale DECIMAL(10,2) NOT NULL,
  in_stock TINYINT(1) NOT NULL DEFAULT 1,
  is_on_sale TINYINT(1) NOT NULL DEFAULT 0,
  image_path VARCHAR(255) NULL,
  badge_text VARCHAR(30) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  order_number VARCHAR(30) NOT NULL UNIQUE,
  status ENUM('pending','confirmed','packed','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  payment_method ENUM('cod','online') NOT NULL DEFAULT 'cod',
  payment_status ENUM('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid',
  subtotal DECIMAL(10,2) NOT NULL,
  shipping_fee DECIMAL(10,2) NOT NULL DEFAULT 0,
  discount DECIMAL(10,2) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL,
  customer_name VARCHAR(120) NOT NULL,
  customer_phone VARCHAR(20) NOT NULL,
  customer_email VARCHAR(190) NOT NULL,
  address_line1 VARCHAR(190) NOT NULL,
  address_line2 VARCHAR(190) NULL,
  city VARCHAR(120) NOT NULL,
  state VARCHAR(120) NOT NULL,
  pincode VARCHAR(10) NOT NULL,
  notes TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(190) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  line_total DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE newsletter_subscribers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE coupons (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL UNIQUE,
  type ENUM('fixed','percent') NOT NULL DEFAULT 'fixed',
  value DECIMAL(10,2) NOT NULL,
  min_subtotal DECIMAL(10,2) NOT NULL DEFAULT 0,
  max_discount DECIMAL(10,2) NOT NULL DEFAULT 0,
  usage_limit INT UNSIGNED NOT NULL DEFAULT 0,
  used_count INT UNSIGNED NOT NULL DEFAULT 0,
  starts_at DATE NULL,
  ends_at DATE NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE b2b_inquiries (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  company_name VARCHAR(190) NOT NULL,
  contact_person VARCHAR(190) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  business_type VARCHAR(40) NOT NULL,
  gst VARCHAR(40) NULL,
  address TEXT NOT NULL,
  city VARCHAR(120) NOT NULL,
  state VARCHAR(120) NOT NULL,
  pincode VARCHAR(10) NOT NULL,
  requirements TEXT NOT NULL,
  products TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO coupons (code, type, value, min_subtotal, max_discount, usage_limit, used_count, starts_at, ends_at, is_active) VALUES
('CHAATKU10', 'percent', 10.00, 199.00, 100.00, 0, 0, NULL, NULL, 1);

INSERT INTO categories (name, slug) VALUES
('Himalayan Salt & Black Pepper', 'himalayan-salt-black-pepper'),
('Cheese', 'cheese'),
('Tomato', 'tomato'),
('Peri Peri', 'peri-peri'),
('Cream & Onion', 'cream-onion'),
('Himalayan Salt Pudina', 'himalayan-salt-pudina'),
('Organic Plain', 'organic-plain'),
('Combo Packs', 'combo-packs');

INSERT INTO products (category_id, name, slug, short_desc, description, price_mrp, price_sale, in_stock, is_on_sale, image_path, badge_text) VALUES
((SELECT id FROM categories WHERE slug='organic-plain'), 'Organic Plain Makhana - Premium Quality', 'organic-plain-makhana-premium-quality', NULL, 'Premium quality roasted makhana sourced and packed in India.', 295.00, 240.00, 1, 1, 'uploads/products/organic-plain.png', '-18%'),
((SELECT id FROM categories WHERE slug='combo-packs'), 'Combo Pack - Cheese & Peri Peri', 'combo-pack-cheese-peri-peri', NULL, 'A combo pack featuring two popular flavours.', 400.00, 294.00, 1, 1, 'uploads/products/combo-cheese-peri.png', '-27%'),
((SELECT id FROM categories WHERE slug='himalayan-salt-black-pepper'), 'Himalayan Salt & Black Pepper Makhana', 'himalayan-salt-black-pepper-makhana', NULL, 'Classic Himalayan salt with black pepper seasoning.', 249.00, 159.00, 1, 1, 'uploads/products/salt-pepper.png', '-36%'),
((SELECT id FROM categories WHERE slug='cheese'), 'Cheese Makhana', 'cheese-makhana', NULL, 'Cheesy, crunchy and satisfying.', 249.00, 159.00, 1, 1, 'uploads/products/cheese.png', '-36%'),
((SELECT id FROM categories WHERE slug='tomato'), 'Tomato Makhana', 'tomato-makhana', NULL, 'Tangy tomato flavour for everyday snacking.', 249.00, 159.00, 1, 1, 'uploads/products/tomato.png', '-36%'),
((SELECT id FROM categories WHERE slug='himalayan-salt-pudina'), 'Himalayan Salt Pudina Makhana', 'himalayan-salt-pudina-makhana', NULL, 'Refreshing pudina with Himalayan salt.', 249.00, 159.00, 1, 1, 'uploads/products/pudina.png', '-36%'),
((SELECT id FROM categories WHERE slug='peri-peri'), 'Peri Peri Roasted Makhana | Spicy & Crunchy', 'peri-peri-roasted-makhana-spicy-crunchy', NULL, 'Spicy peri peri seasoning with a crunchy bite.', 249.00, 159.00, 1, 1, 'uploads/products/peri-peri.png', '-36%'),
((SELECT id FROM categories WHERE slug='cream-onion'), 'Cream & Onion Makhana', 'cream-onion-makhana', NULL, 'Creamy onion flavour loved by all ages.', 249.00, 159.00, 1, 1, 'uploads/products/cream-onion.png', '-36%');

INSERT INTO users (name, email, phone, password_hash, role) VALUES
('Admin', 'admin@makhana.local', '9999999999', '$2y$10$6g4O5d3gQ2G8xO/6T9xVZ.g7qgZpQp3hGJ7U3wK8hWfXvBfS9vW2u', 'admin');
