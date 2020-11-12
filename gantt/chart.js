gantt
dateFormat  DD-MM-YYYY
title       Telemetry Data Processing Timeline

section Preparation
Create repository                 :active,    des1, 2020-11-12, 1d
Roles assignemnt                  :           des2, 2020-11-12, 1d
Plan the strategy                 :           des3, 2020-11-12, 1d
Create TODO list                  :           des4, 2020-11-12, 1d

section Development
Design and develop front-end      : des5, after des4, 5d
TWIG optimization                 : des51, after des5, 3d
Design and develop back-end       : des6, after des5, 7d
Database implementation           : des7, after des6, 5d
Create tests                      : des8, after des7, 5d

section Code Analysis
Quality Assurance                 :des9, after des8, 3d
Code Validation (W3C)             :des91, after des9, 2d
PSR1, PSR12, PHP-FIG              :des92, after des91, 5d

section EE M2M Server Integration
Integration                       :des10, after des9, 5d
Testing                           :des11, after des10, 2d

section Documentation
Docblock comments and code cleanup               :des12, after des11, 8d
Writing docs                                     :des13, after des11, 4d

section Viva preparation
Fixing issues                   : des14,after des13, 10d
Polishing                       : des15, after des14, 7d
Presentation preparation        :des16, after des15, 5d