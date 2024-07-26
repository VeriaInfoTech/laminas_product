
CREATE TABLE `product_item` (
                                `id` int(10) UNSIGNED NOT NULL,
                                `title` varchar(255) NOT NULL DEFAULT '',
                                `slug` varchar(255) NOT NULL DEFAULT '',
                                `type` varchar(64) DEFAULT NULL,
                                `status` int(10) UNSIGNED NOT NULL DEFAULT 0,
                                `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
                                `time_create` int(10) UNSIGNED NOT NULL DEFAULT 0,
                                `time_update` int(10) UNSIGNED NOT NULL DEFAULT 0,
                                `time_delete` int(10) UNSIGNED NOT NULL DEFAULT 0,
                                `information` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`information`)),
                                `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content_item`
--
ALTER TABLE `product_item`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--
