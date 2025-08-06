USE TFCMOBILE;
GO

PRINT 'Starting BME Performance Optimization for TFCMOBILE database';
GO

PRINT 'Creating Cust_BulkRun performance indexes';
GO
IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_RunNo_Status' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_RunNo_Status 
    ON Cust_BulkRun (RunNo, Status) 
    INCLUDE (FormulaId, NoOfBatches, RecDate)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_RunNo_Status';
END
ELSE
    PRINT 'IX_Cust_BulkRun_RunNo_Status already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_FormulaId' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_FormulaId 
    ON Cust_BulkRun (FormulaId) 
    INCLUDE (RunNo, Status, NoOfBatches, RecDate)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_FormulaId';
END
ELSE
    PRINT 'IX_Cust_BulkRun_FormulaId already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_RecDate' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_RecDate 
    ON Cust_BulkRun (RecDate DESC) 
    INCLUDE (RunNo, FormulaId, Status, NoOfBatches)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_RecDate';
END
ELSE
    PRINT 'IX_Cust_BulkRun_RecDate already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_Search' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_Search 
    ON Cust_BulkRun (Status, FormulaId) 
    INCLUDE (RunNo, NoOfBatches, RecDate)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_Search';
END
ELSE
    PRINT 'IX_Cust_BulkRun_Search already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_RunNo_BatchNo' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_RunNo_BatchNo 
    ON Cust_BulkRun (RunNo, BatchNo) 
    INCLUDE (FormulaId, Status, NoOfBatches, RecDate)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_RunNo_BatchNo';
END
ELSE
    PRINT 'IX_Cust_BulkRun_RunNo_BatchNo already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_CustBulkRun_BatchNo_RunNo' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_CustBulkRun_BatchNo_RunNo 
    ON Cust_BulkRun (BatchNo, RunNo)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_CustBulkRun_BatchNo_RunNo';
END
ELSE
    PRINT 'IX_CustBulkRun_BatchNo_RunNo already exists';
GO

IF OBJECT_ID('PNMAST', 'U') IS NOT NULL
BEGIN
    PRINT 'Creating PNMAST performance indexes for batch selection';
    
    IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_PNMAST_BatchSelection_Performance' AND object_id = OBJECT_ID('PNMAST'))
    BEGIN
        CREATE NONCLUSTERED INDEX IX_PNMAST_BatchSelection_Performance 
        ON PNMAST (BatchType, Status, EntryDate, ProcessCellId)
        INCLUDE (FormulaID, BatchNo, BatchWeight, SchStartDate, BatchTicketDate)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
              DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
        PRINT 'Created IX_PNMAST_BatchSelection_Performance';
    END
    ELSE
        PRINT 'IX_PNMAST_BatchSelection_Performance already exists';

    IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_PNMAST_FormulaID_Performance' AND object_id = OBJECT_ID('PNMAST'))
    BEGIN
        CREATE NONCLUSTERED INDEX IX_PNMAST_FormulaID_Performance 
        ON PNMAST (FormulaID, BatchType, Status)
        INCLUDE (BatchNo, BatchWeight, SchStartDate, BatchTicketDate)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
              DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
        PRINT 'Created IX_PNMAST_FormulaID_Performance';
    END
    ELSE
        PRINT 'IX_PNMAST_FormulaID_Performance already exists';

    IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_PNMAST_BatchWeight_Performance' AND object_id = OBJECT_ID('PNMAST'))
    BEGIN
        CREATE NONCLUSTERED INDEX IX_PNMAST_BatchWeight_Performance 
        ON PNMAST (BatchWeight, BatchType, Status)
        INCLUDE (FormulaID, BatchNo, SchStartDate, BatchTicketDate)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
              DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
        PRINT 'Created IX_PNMAST_BatchWeight_Performance';
    END
    ELSE
        PRINT 'IX_PNMAST_BatchWeight_Performance already exists';

    IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_PNMAST_DateOrdering_Performance' AND object_id = OBJECT_ID('PNMAST'))
    BEGIN
        CREATE NONCLUSTERED INDEX IX_PNMAST_DateOrdering_Performance 
        ON PNMAST (BatchTicketDate DESC, BatchNo DESC)
        WHERE (BatchType = 'M' AND Status = 'R')
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
              DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
        PRINT 'Created IX_PNMAST_DateOrdering_Performance';
    END
    ELSE
        PRINT 'IX_PNMAST_DateOrdering_Performance already exists';

    PRINT 'PNMAST indexes created for TFCMOBILE';
END
ELSE
BEGIN
    PRINT 'PNMAST table not found in TFCMOBILE';
END
GO

PRINT 'Cust_BulkRun indexes created for TFCMOBILE';
GO

USE TFCPILOT3;
GO

PRINT 'Starting BME Performance Optimization for TFCPILOT3 database';
GO

PRINT 'Creating Cust_BulkRun performance indexes';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_RunNo_Status' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_RunNo_Status 
    ON Cust_BulkRun (RunNo, Status) 
    INCLUDE (FormulaId, NoOfBatches, RecDate)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_RunNo_Status';
END
ELSE
    PRINT 'IX_Cust_BulkRun_RunNo_Status already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_FormulaId' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_FormulaId 
    ON Cust_BulkRun (FormulaId) 
    INCLUDE (RunNo, Status, NoOfBatches, RecDate)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_FormulaId';
END
ELSE
    PRINT 'IX_Cust_BulkRun_FormulaId already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_RecDate' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_RecDate 
    ON Cust_BulkRun (RecDate DESC) 
    INCLUDE (RunNo, FormulaId, Status, NoOfBatches)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_RecDate';
END
ELSE
    PRINT 'IX_Cust_BulkRun_RecDate already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_Search' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_Search 
    ON Cust_BulkRun (Status, FormulaId) 
    INCLUDE (RunNo, NoOfBatches, RecDate)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_Search';
END
ELSE
    PRINT 'IX_Cust_BulkRun_Search already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_Cust_BulkRun_RunNo_BatchNo' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_Cust_BulkRun_RunNo_BatchNo 
    ON Cust_BulkRun (RunNo, BatchNo) 
    INCLUDE (FormulaId, Status, NoOfBatches, RecDate)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_Cust_BulkRun_RunNo_BatchNo';
END
ELSE
    PRINT 'IX_Cust_BulkRun_RunNo_BatchNo already exists';
GO

IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_CustBulkRun_BatchNo_RunNo' AND object_id = OBJECT_ID('Cust_BulkRun'))
BEGIN
    CREATE NONCLUSTERED INDEX IX_CustBulkRun_BatchNo_RunNo 
    ON Cust_BulkRun (BatchNo, RunNo)
    WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
          DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
    PRINT 'Created IX_CustBulkRun_BatchNo_RunNo';
END
ELSE
    PRINT 'IX_CustBulkRun_BatchNo_RunNo already exists';
GO

IF OBJECT_ID('PNMAST', 'U') IS NOT NULL
BEGIN
    PRINT 'Creating PNMAST performance indexes for batch selection';
    
    IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_PNMAST_BatchSelection_Performance' AND object_id = OBJECT_ID('PNMAST'))
    BEGIN
        CREATE NONCLUSTERED INDEX IX_PNMAST_BatchSelection_Performance 
        ON PNMAST (BatchType, Status, EntryDate, ProcessCellId)
        INCLUDE (FormulaID, BatchNo, BatchWeight, SchStartDate, BatchTicketDate)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
              DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
        PRINT 'Created IX_PNMAST_BatchSelection_Performance';
    END
    ELSE
        PRINT 'IX_PNMAST_BatchSelection_Performance already exists';

    IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_PNMAST_FormulaID_Performance' AND object_id = OBJECT_ID('PNMAST'))
    BEGIN
        CREATE NONCLUSTERED INDEX IX_PNMAST_FormulaID_Performance 
        ON PNMAST (FormulaID, BatchType, Status)
        INCLUDE (BatchNo, BatchWeight, SchStartDate, BatchTicketDate)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
              DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
        PRINT 'Created IX_PNMAST_FormulaID_Performance';
    END
    ELSE
        PRINT 'IX_PNMAST_FormulaID_Performance already exists';

    IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_PNMAST_BatchWeight_Performance' AND object_id = OBJECT_ID('PNMAST'))
    BEGIN
        CREATE NONCLUSTERED INDEX IX_PNMAST_BatchWeight_Performance 
        ON PNMAST (BatchWeight, BatchType, Status)
        INCLUDE (FormulaID, BatchNo, SchStartDate, BatchTicketDate)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
              DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
        PRINT 'Created IX_PNMAST_BatchWeight_Performance';
    END
    ELSE
        PRINT 'IX_PNMAST_BatchWeight_Performance already exists';

    IF NOT EXISTS (SELECT name FROM sys.indexes WHERE name = 'IX_PNMAST_DateOrdering_Performance' AND object_id = OBJECT_ID('PNMAST'))
    BEGIN
        CREATE NONCLUSTERED INDEX IX_PNMAST_DateOrdering_Performance 
        ON PNMAST (BatchTicketDate DESC, BatchNo DESC)
        WHERE (BatchType = 'M' AND Status = 'R')
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, 
              DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON);
        PRINT 'Created IX_PNMAST_DateOrdering_Performance';
    END
    ELSE
        PRINT 'IX_PNMAST_DateOrdering_Performance already exists';

    PRINT 'PNMAST indexes created for TFCPILOT3';
END
ELSE
BEGIN
    PRINT 'PNMAST table not found in TFCPILOT3';
END
GO

PRINT 'Cust_BulkRun indexes created for TFCPILOT3';
GO

PRINT 'All basic indexes created successfully for both databases';

PRINT 'Verifying created indexes';
GO

SELECT 
    DB_NAME() AS DatabaseName,
    OBJECT_NAME(i.object_id) AS TableName,
    i.name AS IndexName,
    i.type_desc AS IndexType,
    CASE WHEN i.is_disabled = 0 THEN 'Enabled' ELSE 'Disabled' END AS Status
FROM sys.indexes i
WHERE (OBJECT_NAME(i.object_id) = 'Cust_BulkRun' AND i.name LIKE 'IX_%')
   OR (OBJECT_NAME(i.object_id) = 'PNMAST' AND i.name LIKE 'IX_PNMAST_%')
ORDER BY OBJECT_NAME(i.object_id), i.name;
GO