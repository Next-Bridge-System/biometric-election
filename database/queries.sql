-- Query to get the total number of advocates in each age category
-- for the period between July 1, 2024, and April 30, 2025
-- with specific application statuses (1 and 8)
-- and the total payments made by them
SELECT
  CASE
    WHEN lc.age < 25 THEN 'Up to 25'
    WHEN lc.age >= 25 AND lc.age < 30 THEN '25 to 30'
    WHEN lc.age >= 30 AND lc.age < 35 THEN '30 to 35'
    WHEN lc.age >= 35 AND lc.age < 40 THEN '35 to 40'
    WHEN lc.age >= 40 AND lc.age < 50 THEN '40 to 50'
    WHEN lc.age >= 50 AND lc.age < 60 THEN '50 to 60'
    ELSE 'Above 60'
  END AS age_category,
  COUNT(DISTINCT lc.id) AS total_advocates,
  SUM(p.amount) AS total_payments
FROM lower_courts lc
LEFT JOIN payments p ON p.lower_court_id = lc.id
WHERE lc.final_submitted_at BETWEEN '2024-07-01' AND '2025-04-30'
  AND lc.app_status IN (1, 8)
GROUP BY age_category
ORDER BY
  CASE
    WHEN age_category = 'Up to 25' THEN 1
    WHEN age_category = '25 to 30' THEN 2
    WHEN age_category = '30 to 35' THEN 3
    WHEN age_category = '35 to 40' THEN 4
    WHEN age_category = '40 to 50' THEN 5
    WHEN age_category = '50 to 60' THEN 6
    ELSE 7
  END

-- Query to get the total number of intimations in each age category
-- for the period between July 1, 2024, and April 30, 2025
-- with specific application statuses (1 and 8)
-- and the total payments made by them
  SELECT
  CASE
    WHEN age < 35 THEN 'Up to 35'
    WHEN age >= 35 AND age < 40 THEN '35 to 40'
    WHEN age >= 40 AND age < 50 THEN '40 to 50'
    WHEN age >= 50 AND age < 60 THEN '50 to 60'
    ELSE 'Above 60'
  END AS age_category,
  COUNT(*) AS total_advocates,
    SUM(p.amount) AS total_payments
FROM applications
LEFT JOIN payments p ON p.application_id = applications.id
WHERE final_submitted_at BETWEEN '2024-07-01' AND '2025-04-30'
AND applications.application_status in (1,8)
GROUP BY age_category
ORDER BY
  CASE
    WHEN age_category = 'Up to 35' THEN 1
    WHEN age_category = '35 to 40' THEN 2
    WHEN age_category = '40 to 50' THEN 3
    WHEN age_category = '50 to 60' THEN 4
    ELSE 5
  END

-- Query to get the total number of lawyer requests in each sub-category
-- for the period between July 1, 2023, and April 30, 2024
-- and the total number of requests
SELECT 
    sc.name AS sub_category_name,
    COUNT(lr.id) AS total_requests,
    SUM(p.amount) AS total_payments
FROM 
    lawyer_requests lr
JOIN 
    lawyer_request_sub_categories sc 
    ON lr.lawyer_request_sub_category_id = sc.id
LEFT JOIN 
    payments p 
    ON p.lawyer_request_id = lr.id
WHERE 
    lr.created_at BETWEEN '2023-07-01' AND '2024-04-30'
GROUP BY 
    sc.name
ORDER BY 
    total_requests DESC

-- Query to get the total number of advocates high courts
-- for the period between July 1, 2024, and April 30, 2025
-- with specific application statuses (1 and 8)
-- and the total payments made by them
SELECT 
    COUNT(*) AS total_advocates,
        SUM(p.amount) AS total_payments
FROM 
    high_courts
    LEFT JOIN 
    payments p 
    ON p.high_court_id = high_courts.id
WHERE 
    final_submitted_at BETWEEN '2024-07-01' AND '2025-04-30'
    and high_courts.app_status in (1,8)

-- Query to get the total number of advocates with foreign degrees
-- for the period between July 1, 2024, and April 30, 2025
-- and the total payments made by them
SELECT 
    COUNT(*) AS total_foreign_degree_advocates,
            SUM(p.amount) AS total_payments
FROM 
    lower_courts
    LEFT JOIN 
    payments p 
    ON p.lower_court_id = lower_courts.id
WHERE 
    degree_place = 3
    AND final_submitted_at BETWEEN '2024-07-01' AND '2025-04-30';
