CREATE TABLE IF NOT EXISTS `palm_inspections` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `batch_id` VARCHAR(50) NOT NULL,
  `timestamp` DATETIME NOT NULL,
  `supplier_name` VARCHAR(100) NOT NULL,
  `truck_license` VARCHAR(20) NOT NULL,
  `weight_tons` DECIMAL(10,2) NOT NULL,
  `ripeness_ripe_pct` DECIMAL(5,2) NOT NULL,
  `ripeness_unripe_pct` DECIMAL(5,2) NOT NULL,
  `ripeness_overripe_pct` DECIMAL(5,2) NOT NULL,
  `avg_oil_content_pct` DECIMAL(5,2) NOT NULL,
  `estimated_oer_pct` DECIMAL(5,2) NOT NULL,
  `ai_confidence_score` DECIMAL(5,2) NOT NULL,
  `quality_grade` VARCHAR(20) NOT NULL,
  `status` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `palm_inspections` (`batch_id`, `timestamp`, `supplier_name`, `truck_license`, `weight_tons`, `ripeness_ripe_pct`, `ripeness_unripe_pct`, `ripeness_overripe_pct`, `avg_oil_content_pct`, `estimated_oer_pct`, `ai_confidence_score`, `quality_grade`, `status`) VALUES
('BATCH-20261005-01', '2026-10-05 08:15:00', 'ลานเทลุงสมบูรณ์', '81-2041', 12.50, 85.00, 10.00, 5.00, 21.40, 19.20, 98.20, 'Grade A', 'Approved'),
('BATCH-20261005-02', '2026-10-05 08:42:00', 'เกษตรกรกลุ่ม A', '80-1123', 8.20, 60.00, 35.00, 5.00, 17.10, 15.50, 95.50, 'Grade C', 'Warning'),
('BATCH-20261005-03', '2026-10-05 09:10:00', 'ลานเทชุมพรสมาร์ท', '82-9981', 15.00, 92.00, 3.00, 5.00, 23.10, 20.80, 99.10, 'Grade A+', 'Approved');
