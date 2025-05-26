SELECT hcr_no_hc, COUNT(*) duplicate_count FROM gc_high_courts GROUP BY hcr_no_hc HAVING duplicate_count > 1;
SELECT reg_no_lc, COUNT(*) duplicate_count FROM gc_lower_courts GROUP BY reg_no_lc HAVING duplicate_count > 1;
