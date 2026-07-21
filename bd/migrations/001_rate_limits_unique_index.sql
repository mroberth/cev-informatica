ALTER TABLE `rate_limits`
  ADD UNIQUE KEY `uq_ip_endpoint` (`ip_address`, `endpoint`);
