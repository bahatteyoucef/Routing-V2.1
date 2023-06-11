---------------
[HH_Customer] :
---------------

CREATE TABLE [dbo].[HH_Customer](
	[CustomerNo] [nvarchar](15) NOT NULL,
	[CategoryNo] [nvarchar](15) NULL,
	[BusinessId] [nvarchar](15) NULL,
	[SalesSectorNo] [nvarchar](30) NULL,
	[ClassId] [nvarchar](15) NULL,
	[TermsId] [nvarchar](15) NULL,
	[SalesmanNo] [nvarchar](15) NULL,
	[CustomerNameE] [nvarchar](50) NULL,
	[CustomerNameA] [nvarchar](50) NULL,
	[Address] [nvarchar](100) NULL,
	[AreaNo] [nvarchar](60) NULL,
	[CityNo] [nvarchar](60) NULL,
	[DistrictNo] [nvarchar](30) NULL,
	[RegionNo] [nvarchar](15) NULL,
	[Tel] [nvarchar](50) NULL,
	[StopOrder] [tinyint] NULL,
	[BadPayer] [tinyint] NULL,
	[StopDate] [datetime] NULL,
	[ForceCreditLimit] [tinyint] NULL,
	[CreditLimit] [decimal](20, 9) NULL,
	[AllowCheck] [tinyint] NULL,
	[AllowDeferred] [tinyint] NULL,
	[AllowCash] [tinyint] NULL,
	[ContactType] [int] NOT NULL,
	[PriceId] [nvarchar](15) NULL,
	[TaxId] [nvarchar](50) NULL,
	[StopOutLimit] [tinyint] NOT NULL,
	[UnpaidInvoiceCustomerWarning] [tinyint] NULL,
	[CustomerServiceManNo] [nvarchar](15) NULL,
	[CustomerType] [nvarchar](10) NULL,
	[CustomerStatus] [int] NULL,
	[Mobile] [nvarchar](35) NULL,
	[Balance] [float] NULL,
	[MerchType] [int] NULL,
	[Sat] [bit] NULL,
	[Sun] [bit] NULL,
	[Mon] [bit] NULL,
	[Tue] [bit] NULL,
	[Wed] [bit] NULL,
	[Thu] [bit] NULL,
	[Fri] [bit] NULL,
	[TargetNPS] [int] NULL,
	[ActualNPS] [int] NULL,
	[ToVisit] [bit] NULL,
	[BUID] [nvarchar](15) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[today] [int] NULL,
	[TermsBranch] [nvarchar](15) NULL,
	[VisitOrder] [int] NULL,
	[ActualAvg] [int] NULL,
	[TargetAvg] [int] NULL,
	[CashDiscountID] [nvarchar](15) NULL,
	[VisitFrequency] [int] NULL,
	[Latitude] [numeric](24, 15) NULL,
	[Longitude] [numeric](24, 15) NULL,
	[Altitude] [numeric](24, 15) NULL,
	[ErrorRadius] [int] NULL,
	[InvoiceAccount] [nvarchar](15) NULL,
	[RouteID] [nvarchar](60) NULL,
	[Inactive] [tinyint] NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[PaymentMethod] [tinyint] NULL,
	[RecordSource] [tinyint] NULL,
	[AccountNumber] [nvarchar](30) NULL,
	[RequiresManualInvNo] [tinyint] NULL,
	[OrderCeiling] [dbo].[BINUMERIC_L] NOT NULL,
	[AddressA] [nvarchar](100) NULL,
	[AddAddressE] [nvarchar](50) NULL,
	[AddAddressA] [nvarchar](50) NULL,
	[ElectricityNo] [nvarchar](35) NULL,
	[LicenseNo] [nvarchar](35) NULL,
	[GISCode] [nvarchar](30) NULL,
	[AllowCreditCard] [tinyint] NULL,
	[ConfirmedOrders] [dbo].[BINUMERIC_L] NULL,
	[ProductRangeID] [nvarchar](15) NULL,
	[HeadOfficeID] [nvarchar](15) NULL,
	[IsHeadOffice] [tinyint] NOT NULL,
	[ReturnWithoutInvoice] [tinyint] NULL,
	[HasCreditControlArea] [tinyint] NULL,
	[PotCustomerRef] [nvarchar](15) NULL,
	[MandatoryPhoto] [tinyint] NULL,
	[PointsBalance] [int] NULL,
	[NoOfAllowedRedeemPacks] [int] NULL,
	[ServicePatternSet] [nvarchar](50) NULL,
	[IsPhysicalLocation] [tinyint] NOT NULL,
	[CutomerPhysicalLocation] [nvarchar](15) NULL,
	[PostalCode] [nvarchar](50) NULL,
	[ProofOfVisit] [tinyint] NULL,
	[LegacyCustomerName] [nvarchar](50) NULL,
	[LegacyCustomerAddress] [nvarchar](100) NULL,
	[IsPotential] [tinyint] NULL,
	[RecommendedPriceID] [nvarchar](15) NULL,
	[WindowTypeId] [nvarchar](15) NULL,
	[OpenTime] [datetime] NULL,
	[CloseTime] [datetime] NULL,
	[OpenTimeTW1] [datetime] NULL,
	[CloseTimeTW1] [datetime] NULL,
	[OpenTimeTW2] [datetime] NULL,
	[CloseTimeTW2] [datetime] NULL,
	[ServiceTimeTypeId] [nvarchar](15) NULL,
	[AllowTempCredit] [tinyint] NULL,
	[DefaultTerritory] [nvarchar](15) NULL,
	[StopReasonID] [nvarchar](40) NULL,
	[StopComments] [nvarchar](250) NULL,
	[StopUser] [nvarchar](15) NULL,
	[StopDateTime] [datetime] NULL,
	[AllowEPayment] [tinyint] NULL,
	[PreventSalesmanfromCollectingOutStandingInvoices] [tinyint] NULL,
	[AllowInstallment] [tinyint] NULL,
	[AllowPriceEdit] [tinyint] NULL,
	[MinPriceID] [nvarchar](15) NULL,
	[AllowBankTransfer] [tinyint] NULL,
 CONSTRAINT [PK_HH_Customer] PRIMARY KEY CLUSTERED 
(
	[CustomerNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_Customer] ADD  CONSTRAINT [HH_Customer_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
GO

ALTER TABLE [dbo].[HH_Customer] ADD  CONSTRAINT [DF_HH_Customer_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_Customer] ADD  DEFAULT ((0)) FOR [Inactive]
GO

ALTER TABLE [dbo].[HH_Customer] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

ALTER TABLE [dbo].[HH_Customer] ADD  CONSTRAINT [DF_IsheadOfficeZero]  DEFAULT ((0)) FOR [IsHeadOffice]
GO

ALTER TABLE [dbo].[HH_Customer] ADD  DEFAULT ((0)) FOR [IsPhysicalLocation]
GO

ALTER TABLE [dbo].[HH_Customer] ADD  DEFAULT ((0)) FOR [ProofOfVisit]
GO

----------------------------
[HH_AR_CustomerCategories] :
----------------------------

CREATE TABLE [dbo].[HH_AR_CustomerCategories](
	[CategoryId] [nvarchar](15) NOT NULL,
	[Description] [nvarchar](50) NULL,
	[ArabicDescription] [nvarchar](50) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[buid] [nvarchar](15) NULL,
	[DefaultPriceListID] [nvarchar](15) NULL,
	[DefaultPaymentTerms] [nvarchar](15) NULL,
	[DefaultTermsBranch] [nvarchar](15) NULL,
	[DefaultCreditLimit] [dbo].[BINUMERIC_L] NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
	[RecID] [bigint] NULL,
	[SalesTypeID] [nvarchar](15) NULL,
	[AllowPriceEdit] [tinyint] NULL,
	[EditPriceMinPCT] [decimal](4, 1) NULL,
 CONSTRAINT [PK_HH_AR_CustomerCategories] PRIMARY KEY CLUSTERED 
(
	[CategoryId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_AR_CustomerCategories] ADD  CONSTRAINT [DF_HH_AR_CustomerCategories_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_AR_CustomerCategories] ADD  CONSTRAINT [hh_AR_CustomerCategories_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
GO

ALTER TABLE [dbo].[HH_AR_CustomerCategories] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

----------------
HH_SalesSector :
----------------
CREATE TABLE [dbo].[HH_SalesSector](
	[SectorID] [nvarchar](15) NOT NULL,
	[SectorName] [nvarchar](50) NULL,
	[SectorNameAr] [nvarchar](50) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[TransferPriceID] [nvarchar](15) NULL,
 CONSTRAINT [PK_HH_SalesSector] PRIMARY KEY CLUSTERED 
(
	[SectorID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_SalesSector] ADD  CONSTRAINT [DF__HH_SalesS__rowgu__694D1E69]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

--------------------
HH_AR_PaymentTerms :
--------------------
CREATE TABLE [dbo].[HH_AR_PaymentTerms](
	[TermsBranch] [nvarchar](15) NOT NULL,
	[TermsId] [nvarchar](15) NOT NULL,
	[Description] [nvarchar](50) NULL,
	[ArabicDescription] [nvarchar](50) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[buid] [nvarchar](15) NULL,
	[PaymentDays] [int] NULL,
	[MaxOpenInv] [int] NULL,
	[MaxOverdueInv] [int] NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
	[PartialPayment] [tinyint] NULL,
	[DueDateValue] [tinyint] NULL,
	[AllowInstallment] [tinyint] NULL,
	[MinInstallment] [int] NULL,
	[MaxInstallment] [int] NULL,
 CONSTRAINT [PK_HH_AR_PaymentTerms] PRIMARY KEY CLUSTERED 
(
	[TermsBranch] ASC,
	[TermsId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_AR_PaymentTerms] ADD  CONSTRAINT [DF_HH_AR_PaymentTerms_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_AR_PaymentTerms] ADD  CONSTRAINT [DF_HH_AR_PaymentTerms_buid]  DEFAULT ('1') FOR [buid]
GO

ALTER TABLE [dbo].[HH_AR_PaymentTerms] ADD  DEFAULT ((0)) FOR [MaxOpenInv]
GO

ALTER TABLE [dbo].[HH_AR_PaymentTerms] ADD  DEFAULT ((0)) FOR [MaxOverdueInv]
GO

ALTER TABLE [dbo].[HH_AR_PaymentTerms] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

ALTER TABLE [dbo].[HH_AR_PaymentTerms] ADD  DEFAULT ((0)) FOR [PartialPayment]
GO

---------------
[HH_Salesman] :
---------------

CREATE TABLE [dbo].[HH_Salesman](
	[SalesmanNo] [nvarchar](15) NOT NULL,
	[SalesmanNameE] [nvarchar](50) NULL,
	[SalesmanNameA] [nvarchar](50) NULL,
	[CategoryId] [nvarchar](15) NULL,
	[BUID] [nvarchar](15) NULL,
	[BranchNo] [nvarchar](15) NULL,
	[SalesManType] [int] NULL,
	[Address] [nvarchar](50) NULL,
	[Tel] [nvarchar](50) NULL,
	[Mobile] [nvarchar](50) NULL,
	[HashPass] [nvarchar](50) NULL,
	[IsUser] [bit] NOT NULL,
	[outOfRouteLimit] [int] NULL,
	[PreFix] [nvarchar](10) NOT NULL,
	[NextOrderNo] [int] NOT NULL,
	[NextVisitNo] [int] NOT NULL,
	[NextPaymentNo] [int] NOT NULL,
	[Book1Start] [int] NOT NULL,
	[Book1End] [int] NOT NULL,
	[Book2Start] [int] NOT NULL,
	[Book2End] [int] NOT NULL,
	[PayBook1Start] [int] NOT NULL,
	[PayBook1End] [int] NOT NULL,
	[PayBook2Start] [int] NOT NULL,
	[PayBook2End] [int] NOT NULL,
	[BookSizeMult] [int] NOT NULL,
	[Password] [nvarchar](15) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[CurrentOutOfRoute] [int] NULL,
	[WareHouse] [nvarchar](15) NULL,
	[AssignedDriverID] [nvarchar](15) NULL,
	[OutofOrderLimit] [int] NULL,
	[SectorID] [nvarchar](15) NULL,
	[UseNewPrintFW] [tinyint] NULL,
	[GPSMinTrackingDist] [int] NULL,
	[GPSMinTrackingTime] [int] NULL,
	[ProofOfVisit] [tinyint] NULL,
	[MaxVisitsWithoutProof] [int] NULL,
	[Permission] [tinyint] NULL,
	[NextCustLocUpdateNo] [nvarchar](15) NULL,
	[Email] [nvarchar](100) NULL,
	[NextRequestNo] [int] NULL,
	[Balance] [dbo].[BINUMERIC_L] NULL,
	[CreditLimit] [dbo].[BINUMERIC_L] NULL,
	[Inactive] [tinyint] NULL,
	[PreferredLanguage] [tinyint] NULL,
	[HWsupport] [tinyint] NULL,
	[RebateDocumentType] [nvarchar](15) NULL,
	[NextReturnNo] [int] NULL,
	[EncPassword] [nvarchar](20) NULL,
	[SyncBeforeVisit] [tinyint] NULL,
	[UploadAfterVisit] [tinyint] NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[JPCustCount] [int] NULL,
	[RecordSource] [tinyint] NULL,
	[Driver] [nvarchar](15) NULL,
	[Helper1] [nvarchar](15) NULL,
	[Helper2] [nvarchar](15) NULL,
	[Helper3] [nvarchar](15) NULL,
	[DefaultCustomerNo] [nvarchar](15) NULL,
	[ERP_Reference] [nvarchar](30) NULL,
	[EnableOrderWithoutPOV] [tinyint] NULL,
	[DefaultMSL] [nvarchar](15) NULL,
	[DisableDayofvisitedit] [tinyint] NULL,
	[SMDistanceRange] [int] NULL,
	[RouteID] [nvarchar](60) NULL,
	[AllowCreditOverride] [tinyint] NULL,
	[CalendarID] [nvarchar](15) NULL,
	[OverDueInvoicesAmountLimit] [dbo].[BINUMERIC_L] NULL,
	[OverDueInvoicesCountLimit] [dbo].[BINUMERIC_L] NULL,
	[OpenInvoicesAmountLimit] [dbo].[BINUMERIC_L] NULL,
	[OpenInvoicesCountLimit] [dbo].[BINUMERIC_L] NULL,
	[TemporaryCredit] [dbo].[BINUMERIC_L] NULL,
	[TemporaryCreditBalance] [dbo].[BINUMERIC_L] NULL,
	[SMSTemplateTypeID] [nvarchar](15) NULL,
	[preventcollect] [tinyint] NULL,
	[CommissionSchemeID] [varchar](15) NULL,
	[LastPasswordRenew] [datetime] NULL,
	[ReturnType] [tinyint] NULL,
	[TransferPriceID] [nvarchar](15) NULL,
	[EnablePrePayment] [tinyint] NULL,
	[CreditCardAccount] [nvarchar](15) NULL,
	[CashJournalNo] [nvarchar](50) NULL,
	[ChequeJournalNo] [nvarchar](50) NULL,
	[LanguageID] [nvarchar](50) NULL,
	[OrderTakerSuggestedValue] [nvarchar](20) NULL,
	[ClassID] [nvarchar](30) NULL,
	[DamagedWarehouse] [nvarchar](15) NULL,
	[EnableManualDiscountOnOrderLine] [int] NULL,
	[ParentEmployee] [nvarchar](15) NULL,
	[OverduePendingPaymentsDays] [int] NULL,
	[IsParent] [tinyint] NOT NULL,
	[LoadingLimit] [dbo].[BINUMERIC_L] NULL,
	[ParentSalesmanID] [nvarchar](15) NULL,
	[UploadTruckNoToERP] [tinyint] NULL,
	[ConsumptionFactor] [float] NOT NULL,
	[RecQtyCalcType] [tinyint] NOT NULL,
	[AllowOnlineReturn] [int] NULL,
	[ReturnWarehouse] [nvarchar](15) NULL,
	[VanIndicator] [tinyint] NULL,
	[mandatoryStockCheckPhoto] [tinyint] NULL,
	[IgnoreCheckingPendingTransactions] [tinyint] NULL,
	[ReplenishmentTemplateID] [nvarchar](15) NULL,
	[EnableProforma] [tinyint] NULL,
	[CreditCardReceiptMethod] [nvarchar](15) NULL,
	[ChequeReceiptMethod] [nvarchar](15) NULL,
	[CashReceiptMethod] [nvarchar](15) NULL,
	[DefaultTerritory] [nvarchar](15) NULL,
	[SalesbuzzMobileClient] [tinyint] NULL,
	[DisableLoadWithBalance] [tinyint] NULL,
	[StopReasonID] [nvarchar](15) NULL,
	[StopComments] [nvarchar](250) NULL,
	[StopUser] [nvarchar](15) NULL,
	[StopDateTime] [datetime] NULL,
	[AllowOnlineConfirm] [tinyint] NULL,
	[AllowOnlineEdit] [tinyint] NULL,
	[AllowOnlineClose] [tinyint] NULL,
	[MaxOfflineInvoices] [tinyint] NULL,
	[CommissionType] [tinyint] NULL,
	[CommissionValue] [float] NULL,
	[RouteOrigin] [tinyint] NULL,
	[EnableAutoArrive] [tinyint] NULL,
	[Mode] [tinyint] NULL,
	[AllowPriceEdit] [tinyint] NULL,
	[TransferReceiptMethod] [nvarchar](15) NULL,
 CONSTRAINT [PK_HH_Salesman] PRIMARY KEY CLUSTERED 
(
	[SalesmanNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [HH_Salesman_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_SalesManType]  DEFAULT (2) FOR [SalesManType]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_IsUser]  DEFAULT (0) FOR [IsUser]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PreFix]  DEFAULT (N'espace') FOR [PreFix]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_NextOrderNo]  DEFAULT (0) FOR [NextOrderNo]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_NextVisitNo]  DEFAULT (0) FOR [NextVisitNo]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_NextPaymentNo]  DEFAULT (0) FOR [NextPaymentNo]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Book1Start]  DEFAULT (5) FOR [Book1Start]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Book1End]  DEFAULT (0) FOR [Book1End]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Book2Start]  DEFAULT (0) FOR [Book2Start]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Book2End]  DEFAULT (0) FOR [Book2End]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PayBook1Start]  DEFAULT (0) FOR [PayBook1Start]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PayBook1End]  DEFAULT (0) FOR [PayBook1End]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PayBook2Start]  DEFAULT (0) FOR [PayBook2Start]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PayBook2End]  DEFAULT (0) FOR [PayBook2End]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_BookSizeMult]  DEFAULT (1) FOR [BookSizeMult]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Password]  DEFAULT ((123)) FOR [Password]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [NextRequestNo]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [Inactive]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [PreferredLanguage]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [HWsupport]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [NextReturnNo]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [SyncBeforeVisit]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [UploadAfterVisit]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [EnableOrderWithoutPOV]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_DisableDayofvisitedit]  DEFAULT ((0)) FOR [DisableDayofvisitedit]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [ReturnType]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [IsParent]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((1)) FOR [ConsumptionFactor]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [RecQtyCalcType]
GO

ALTER TABLE [dbo].[HH_Salesman] ADD  DEFAULT ((0)) FOR [CommissionType]
GO


--------------------
HH_AR_SalesmenCats :
--------------------

CREATE TABLE [dbo].[HH_AR_SalesmenCats](
	[CategoryId] [nvarchar](15) NOT NULL,
	[Description] [nvarchar](50) NULL,
	[ArabicDescription] [nvarchar](50) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[buid] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
	[CommissionSchemeID] [varchar](15) NULL,
 CONSTRAINT [PK_HH_AR_SalesmenCats] PRIMARY KEY CLUSTERED 
(
	[CategoryId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_AR_SalesmenCats] ADD  CONSTRAINT [DF_HH_AR_SalesmenCats_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_AR_SalesmenCats] ADD  CONSTRAINT [HH_AR_SalesmenCats_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
GO

ALTER TABLE [dbo].[HH_AR_SalesmenCats] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

-----------------
HH_IC_Warehouse :
-----------------

CREATE TABLE [dbo].[HH_IC_Warehouse](
	[WarehouseID] [nvarchar](15) NOT NULL,
	[L1Description] [nvarchar](50) NULL,
	[L2Description] [nvarchar](50) NULL,
	[Category] [nvarchar](15) NULL,
	[BUID] [nvarchar](15) NULL,
	[WarehouseType] [tinyint] NOT NULL,
	[TransWarehouseID] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[PalletNo] [dbo].[BINUMERIC_L] NULL,
	[RecordSource] [tinyint] NULL,
	[DriverID] [nvarchar](15) NULL,
	[StdPalletNo] [dbo].[BINUMERIC_L] NULL,
	[VanCeiling] [dbo].[BINUMERIC_L] NULL,
	[Locked] [tinyint] NULL,
	[LockOwner] [nvarchar](50) NULL,
	[SecUOMNo] [dbo].[BINUMERIC_L] NOT NULL,
	[IsDelivery] [tinyint] NULL,
	[IntegrationType] [nvarchar](15) NULL,
	[Location] [nvarchar](50) NULL,
	[latitude] [dbo].[BINUMERIC] NULL,
	[longitude] [dbo].[BINUMERIC] NULL,
	[Altitude] [dbo].[BINUMERIC] NULL,
	[ErrorRadius] [int] NULL,
	[ERPOrderType] [nvarchar](50) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
 CONSTRAINT [PK_HH_IC_Warehouse] PRIMARY KEY CLUSTERED 
(
	[WarehouseID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_IC_Warehouse] ADD  CONSTRAINT [HH_IC_Warehouse_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
GO

ALTER TABLE [dbo].[HH_IC_Warehouse] ADD  DEFAULT ((0)) FOR [WarehouseType]
GO

ALTER TABLE [dbo].[HH_IC_Warehouse] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

ALTER TABLE [dbo].[HH_IC_Warehouse] ADD  DEFAULT ((0)) FOR [Locked]
GO

ALTER TABLE [dbo].[HH_IC_Warehouse] ADD  CONSTRAINT [DF__HH_IC_Warehouse_SecUOMNo]  DEFAULT ((0)) FOR [SecUOMNo]
GO

ALTER TABLE [dbo].[HH_IC_Warehouse] ADD  DEFAULT ((1)) FOR [IsDelivery]
GO

ALTER TABLE [dbo].[HH_IC_Warehouse] ADD  DEFAULT (newid()) FOR [rowguid]
GO

-------------------
HH_SalesmanSector :
-------------------

CREATE TABLE [dbo].[HH_SalesmanSector](
	[SalesmanNo] [nvarchar](15) NOT NULL,
	[SectorId] [nvarchar](15) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
 CONSTRAINT [PK_HH_SalesmanSector] PRIMARY KEY CLUSTERED 
(
	[SalesmanNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_SalesmanSector] ADD  CONSTRAINT [DF__HH_Salesm__rowgu__6F05F7BF]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

----------
HH_Staff :
----------

CREATE TABLE [dbo].[HH_Staff](
	[StaffID] [nvarchar](15) NOT NULL,
	[Name] [nvarchar](50) NULL,
	[NameA] [nvarchar](50) NULL,
	[Phone] [nvarchar](20) NULL,
	[Type] [tinyint] NOT NULL,
	[BUID] [nvarchar](15) NOT NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
	[AccountNumber] [nvarchar](30) NULL,
	[DefaultCustomerNo] [nvarchar](15) NULL,
	[CreditLimit] [dbo].[BINUMERIC_L] NULL,
	[OverDueInvoicesAmountLimit] [dbo].[BINUMERIC_L] NULL,
	[OverDueInvoicesCountLimit] [dbo].[BINUMERIC_L] NULL,
	[OpenInvoicesAmountLimit] [dbo].[BINUMERIC_L] NULL,
	[OpenInvoicesCountLimit] [dbo].[BINUMERIC_L] NULL,
	[Inactive] [tinyint] NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
 CONSTRAINT [PK_HH_Staff] PRIMARY KEY CLUSTERED 
(
	[StaffID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_Staff] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

ALTER TABLE [dbo].[HH_Staff] ADD  DEFAULT (newid()) FOR [rowguid]
GO

--------------
SA_Languages :
--------------

CREATE TABLE [dbo].[SA_Languages](
	[LanguageID] [nvarchar](50) NOT NULL,
	[OrientaionID] [tinyint] NULL,
 CONSTRAINT [PK_ SA_Languages] PRIMARY KEY CLUSTERED 
(
	[LanguageID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

-------------
SA_Language :
-------------

CREATE TABLE [dbo].[SA_Language](
	[ID] [tinyint] IDENTITY(1,1) NOT NULL,
	[ShortName] [nchar](2) NOT NULL,
	[Description] [nchar](10) NOT NULL,
 CONSTRAINT [PK_SA_Language] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

---------
HH_Area :
---------

CREATE TABLE [dbo].[HH_Area](
	[RegionNo] [nvarchar](15) NOT NULL,
	[DistrictNo] [nvarchar](30) NOT NULL,
	[CityNo] [nvarchar](60) NOT NULL,
	[AreaNo] [nvarchar](60) NOT NULL,
	[AreaNameE] [nvarchar](25) NULL,
	[AreaNameA] [nvarchar](25) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[buid] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
 CONSTRAINT [PK_HH_hh_Area] PRIMARY KEY CLUSTERED 
(
	[AreaNo] ASC,
	[CityNo] ASC,
	[DistrictNo] ASC,
	[RegionNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_Area] ADD  CONSTRAINT [DF_HH_Area_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_Area] ADD  CONSTRAINT [hh_Area_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
GO

ALTER TABLE [dbo].[HH_Area] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

-----------
[HH_City] :
-----------

CREATE TABLE [dbo].[HH_City](
	[RegionNo] [nvarchar](15) NOT NULL,
	[DistrictNo] [nvarchar](30) NOT NULL,
	[CITYNO] [nvarchar](60) NOT NULL,
	[CityNameE] [nvarchar](25) NULL,
	[CityNameA] [nvarchar](25) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[buid] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
 CONSTRAINT [PK_HH_hh_City] PRIMARY KEY CLUSTERED 
(
	[CITYNO] ASC,
	[DistrictNo] ASC,
	[RegionNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_City] ADD  CONSTRAINT [DF_HH_City_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_City] ADD  CONSTRAINT [hh_City_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
GO

ALTER TABLE [dbo].[HH_City] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

-------------
HH_District :
-------------

CREATE TABLE [dbo].[HH_District](
	[RegionNo] [nvarchar](15) NOT NULL,
	[DistrictNo] [nvarchar](30) NOT NULL,
	[DistrictNameE] [nvarchar](25) NULL,
	[DistrictNameA] [nvarchar](25) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[buid] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
 CONSTRAINT [PK_HH_hh_District] PRIMARY KEY CLUSTERED 
(
	[DistrictNo] ASC,
	[RegionNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_District] ADD  CONSTRAINT [DF_HH_District_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_District] ADD  CONSTRAINT [hh_District_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
GO

ALTER TABLE [dbo].[HH_District] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

-------------
[HH_Region] :
-------------

CREATE TABLE [dbo].[HH_Region](
	[RegionNo] [nvarchar](15) NOT NULL,
	[RegionNameE] [nvarchar](25) NULL,
	[RegionNameA] [nvarchar](25) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[buid] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
 CONSTRAINT [PK_HH_hh_region] PRIMARY KEY CLUSTERED 
(
	[RegionNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_Region] ADD  CONSTRAINT [DF_HH_Region_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_Region] ADD  CONSTRAINT [HH_Region_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
GO

ALTER TABLE [dbo].[HH_Region] ADD  DEFAULT ((0)) FOR [RecordSource]
GO


---------------------
HH_AR_CustomerTypes :
---------------------
CREATE TABLE [dbo].[HH_AR_CustomerTypes](
	[TypeId] [nvarchar](15) NOT NULL,
	[Description] [nvarchar](40) NULL,
	[ArabicDescription] [nvarchar](40) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[buid] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
	[DisplayOrder] [int] NULL,
 CONSTRAINT [PK_HH_AR_CustomerTypes] PRIMARY KEY CLUSTERED 
(
	[TypeId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_AR_CustomerTypes] ADD  CONSTRAINT [DF_HH_AR_CustomerTypes_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_AR_CustomerTypes] ADD  CONSTRAINT [hh_AR_CustomerTypes_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
GO

ALTER TABLE [dbo].[HH_AR_CustomerTypes] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

-----------
HH_Branch :
-----------

CREATE TABLE [dbo].[HH_Branch](
	[BranchNo] [nvarchar](15) NOT NULL,
	[BranchNameE] [nvarchar](50) NULL,
	[BranchNameA] [nvarchar](50) NULL,
	[Address] [text] NULL,
	[ArabicAddress] [text] NULL,
	[TaxRegCode] [nvarchar](30) NULL,
	[SalesTaxRegCode] [nvarchar](30) NULL,
	[BUID] [nvarchar](15) NULL,
	[SalesManBookStart] [int] NOT NULL,
	[SalesManPayBookStart] [int] NOT NULL,
	[SalesManBookSize] [int] NOT NULL,
	[DefaultWareHouseNo] [nvarchar](15) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[password] [nvarchar](15) NULL,
	[SalesTaxNo] [nvarchar](30) NULL,
	[InvHeader1] [nvarchar](200) NOT NULL,
	[InvHeader2] [nvarchar](200) NOT NULL,
	[InvFooter] [nvarchar](200) NOT NULL,
	[BranchServerURL] [nvarchar](100) NULL,
	[RegionNo] [nvarchar](15) NULL,
	[GLAccount] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[CashJournalNameId] [nvarchar](20) NULL,
	[CheckJournalNameId] [nvarchar](20) NULL,
	[RecordSource] [tinyint] NULL,
	[CurrencyID] [nvarchar](15) NULL,
	[ERPDocType] [nvarchar](15) NULL,
	[CashPaymentMethod] [nvarchar](50) NULL,
	[CheckPaymentMethod] [nvarchar](50) NULL,
	[InventJournalName] [nvarchar](150) NULL,
	[DamagedWarehouse] [nvarchar](15) NULL,
	[latitude] [dbo].[BINUMERIC] NULL,
	[longitude] [dbo].[BINUMERIC] NULL,
	[Altitude] [dbo].[BINUMERIC] NULL,
	[ErrorRadius] [int] NULL,
	[ReturnWarehouse] [nvarchar](15) NULL,
	[DefaultVanWarehouse] [nvarchar](15) NULL,
	[PreServiceTimeType] [nvarchar](15) NULL,
	[PostServiceTimeType] [nvarchar](15) NULL,
	[ERPProfitCenter] [nvarchar](40) NULL,
 CONSTRAINT [PK_HH_Branch] PRIMARY KEY CLUSTERED 
(
	[BranchNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_Branch] ADD  CONSTRAINT [HH_Branch_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
GO

ALTER TABLE [dbo].[HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_SalesManBookStart]  DEFAULT (0) FOR [SalesManBookStart]
GO

ALTER TABLE [dbo].[HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_SalesManPayBookStart]  DEFAULT (0) FOR [SalesManPayBookStart]
GO

ALTER TABLE [dbo].[HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_SalesManBookSize]  DEFAULT (0) FOR [SalesManBookSize]
GO

ALTER TABLE [dbo].[HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
GO

ALTER TABLE [dbo].[HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_InvHeader1]  DEFAULT ('') FOR [InvHeader1]
GO

ALTER TABLE [dbo].[HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_InvHeader2]  DEFAULT ('') FOR [InvHeader2]
GO

ALTER TABLE [dbo].[HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_InvFooter]  DEFAULT ('') FOR [InvFooter]
GO

ALTER TABLE [dbo].[HH_Branch] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

-----------
IC_Currency
-----------

CREATE TABLE [dbo].[IC_Currency](
	[CurrencyNo] [nvarchar](15) NOT NULL,
	[Description] [nvarchar](50) NULL,
	[DescriptionA] [nvarchar](50) NULL,
	[Fraction] [nvarchar](50) NULL,
	[Createdby] [nvarchar](20) NULL,
	[ModifiedBy] [nvarchar](20) NULL,
	[CreatedOn] [datetime] NULL,
	[ModifiedOn] [datetime] NULL,
	[RecordSource] [tinyint] NULL,
	[FractionAr] [nvarchar](50) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
 CONSTRAINT [PK_IC_Currency] PRIMARY KEY CLUSTERED 
(
	[CurrencyNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[IC_Currency] ADD  DEFAULT (newid()) FOR [rowguid]
GO

----------
HH_SA_BU :
----------

CREATE TABLE [dbo].[HH_SA_BU](
	[BUID] [nvarchar](15) NOT NULL,
	[Description] [nvarchar](50) NULL,
	[DescriptionA] [nvarchar](50) NULL,
	[ParentBU] [nvarchar](15) NULL,
	[Level] [tinyint] NOT NULL,
	[HasChildren] [tinyint] NULL,
	[ShortCode] [nvarchar](5) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[OrganizationType] [tinyint] NULL,
	[ERPOrganizationID] [nvarchar](15) NULL,
	[MemoLineId] [nvarchar](15) NULL,
	[ERPDistChannelID] [nvarchar](35) NULL,
	[ERPSalesDivisonID] [nvarchar](35) NULL,
	[TypeOfPayment] [tinyint] NULL,
	[CompanyGlAccount] [nvarchar](25) NULL,
	[CurrencyID] [nvarchar](15) NULL,
	[SAPGovernorate] [nvarchar](30) NULL,
	[SAPplant] [nvarchar](30) NULL,
	[SalesTypeID] [nvarchar](15) NULL,
	[SAPSalesOffice] [nvarchar](15) NULL,
	[SAPSalesdistrict] [nvarchar](30) NULL,
	[SAPCompanyCode] [nvarchar](30) NULL,
	[SAPCreditControlArea] [nvarchar](15) NULL,
	[SAPCreditSegment] [nvarchar](15) NULL,
	[SAPSalesOrg] [nvarchar](15) NULL,
	[DefaultWarehouse] [nvarchar](15) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
	[SAPShortCode] [nvarchar](5) NULL,
PRIMARY KEY CLUSTERED 
(
	[BUID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_SA_BU] ADD  CONSTRAINT [hh_sa_bu_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
GO

ALTER TABLE [dbo].[HH_SA_BU] ADD  DEFAULT ((0)) FOR [HasChildren]
GO

ALTER TABLE [dbo].[HH_SA_BU] ADD  DEFAULT ((0)) FOR [TypeOfPayment]
GO

ALTER TABLE [dbo].[HH_SA_BU] ADD  DEFAULT (newid()) FOR [rowguid]
GO

----------------
HH_SA_BUlevels :
----------------

CREATE TABLE [dbo].[HH_SA_BULevels](
	[LevelNo] [tinyint] NOT NULL,
	[Description] [nvarchar](50) NULL,
	[DescriptionA] [nvarchar](50) NULL,
	[LevelLength] [tinyint] NOT NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
PRIMARY KEY CLUSTERED 
(
	[LevelNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

------------
[HH_Route] :
------------

CREATE TABLE [dbo].[HH_Route](
	[RouteID] [nvarchar](60) NOT NULL,
	[RouteNameE] [nvarchar](100) NULL,
	[RouteNameA] [nvarchar](100) NULL,
	[BUID] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[Type] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
	[RouteCategoryID] [nvarchar](15) NULL,
	[RegionNo] [nvarchar](15) NULL,
	[DistrictNo] [nvarchar](30) NULL,
	[CityNo] [nvarchar](60) NULL,
	[AreaNo] [nvarchar](60) NULL,
	[InActive] [tinyint] NULL,
	[RouteChannel] [nvarchar](60) NULL,
	[RouteGTM] [nvarchar](60) NULL,
	[CommissionSchemeID] [nvarchar](15) NULL,
PRIMARY KEY CLUSTERED 
(
	[RouteID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_Route] ADD  DEFAULT ((0)) FOR [InActive]
GO

-------------------------
AR_RouteCategoriesGroup :
-------------------------

CREATE TABLE [dbo].[AR_RouteCategoriesGroup](
	[GroupID] [nvarchar](15) NOT NULL,
	[Description] [nvarchar](50) NULL,
	[DescriptionA] [nvarchar](50) NULL,
	[ParentAccount] [nvarchar](15) NULL,
	[Level] [tinyint] NOT NULL,
	[HasChildren] [tinyint] NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[BUID] [nvarchar](15) NULL,
	[CommissionSchemeID] [nvarchar](15) NULL,
PRIMARY KEY CLUSTERED 
(
	[GroupID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[AR_RouteCategoriesGroup] ADD  DEFAULT ((0)) FOR [HasChildren]
GO


-------------------
[HH_RangeSectors] :
-------------------

CREATE TABLE [dbo].[HH_RangeSectors](
	[RangeID] [nvarchar](15) NOT NULL,
	[SectorID] [nvarchar](15) NOT NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[rowguid] [uniqueidentifier] ROWGUIDCOL  NOT NULL,
 CONSTRAINT [PK_HH_RangeSectors] PRIMARY KEY CLUSTERED 
(
	[SectorID] ASC,
	[RangeID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[HH_RangeSectors] ADD  DEFAULT (newid()) FOR [rowguid]
GO

-----------------------
HH_CustomerSalesTypes :
-----------------------

CREATE TABLE [dbo].[HH_CustomerSalesTypes](
	[SalesTypeID] [nvarchar](30) NOT NULL,
	[Description] [nvarchar](50) NULL,
	[DescriptionAR] [nvarchar](50) NULL,
PRIMARY KEY CLUSTERED 
(
	[SalesTypeID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

--------------
[JPCustomer] :
--------------

CREATE TABLE [dbo].[JPCustomer](
	[CustomerID] [nvarchar](15) NOT NULL,
	[JPID] [int] NOT NULL,
	[Frequency] [tinyint] NULL,
	[StartWeek] [tinyint] NULL,
	[sat] [tinyint] NULL,
	[sun] [tinyint] NULL,
	[mon] [tinyint] NULL,
	[tue] [tinyint] NULL,
	[wed] [tinyint] NULL,
	[thu] [tinyint] NULL,
	[fri] [tinyint] NULL,
	[VisitOrder] [int] NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
	[IsPotential] [int] NOT NULL,
	[UpdateGPSLocation] [int] NULL,
	[TerritoryNo] [nvarchar](15) NULL,
	[EffectiveDate] [datetime] NULL,
 CONSTRAINT [PK_JPCustomer] PRIMARY KEY CLUSTERED 
(
	[CustomerID] ASC,
	[JPID] ASC,
	[IsPotential] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[JPCustomer] ADD  DEFAULT ((0)) FOR [RecordSource]
GO

ALTER TABLE [dbo].[JPCustomer] ADD  DEFAULT ((0)) FOR [IsPotential]
GO

ALTER TABLE [dbo].[JPCustomer]  WITH CHECK ADD  CONSTRAINT [FK_JPCustomer_JourneyPlan] FOREIGN KEY([JPID])
REFERENCES [dbo].[JourneyPlan] ([ID])
ON DELETE CASCADE
GO

ALTER TABLE [dbo].[JPCustomer] CHECK CONSTRAINT [FK_JPCustomer_JourneyPlan]
GO

-----------------------
[AR_TerritoryMapping] :
-----------------------

CREATE TABLE [dbo].[AR_TerritoryMapping](
	[TerritoryID] [nvarchar](50) NOT NULL,
	[SalesMan] [nvarchar](50) NULL,
	[SessionDescription] [nvarchar](200) NOT NULL,
 CONSTRAINT [PK_AR_TerritoryMapping] PRIMARY KEY CLUSTERED 
(
	[TerritoryID] ASC,
	[SessionDescription] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

-------------
JourneyPlan :
-------------

CREATE TABLE [dbo].[JourneyPlan](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[AssignedTO] [nvarchar](15) NULL,
	[BelongsTo] [nvarchar](15) NULL,
	[JPlan] [nvarchar](25) NOT NULL,
	[CityNo] [nvarchar](60) NULL,
	[DistrictNo] [nvarchar](30) NULL,
	[AreaNo] [nvarchar](60) NULL,
	[Type] [nvarchar](15) NULL,
	[parent_id] [nvarchar](15) NULL,
	[ModifiedOn] [datetime] NULL,
	[ModifiedBy] [nvarchar](15) NULL,
	[CreatedOn] [datetime] NULL,
	[Createdby] [nvarchar](15) NULL,
	[RecordSource] [tinyint] NULL,
	[DescriptionE] [nvarchar](50) NULL,
	[DescriptionAr] [nvarchar](50) NULL,
	[TerritoryNo] [nvarchar](255) NULL,
	[BUID] [nvarchar](255) NULL,
	[CustomerCategory] [nvarchar](255) NULL,
	[SalesmanType] [int] NULL,
	[RouteID] [nvarchar](255) NULL,
	[EffectiveDate] [datetime] NULL,
	[IsActive] [tinyint] NULL,
	[EndDate] [datetime] NULL,
	[OrderRouteAssignment] [int] NULL,
 CONSTRAINT [PK_JourneyPlan] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 75) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[JourneyPlan] ADD  CONSTRAINT [DEF_JP_TYPE]  DEFAULT ('Master') FOR [Type]
GO

ALTER TABLE [dbo].[JourneyPlan] ADD  DEFAULT ((0)) FOR [RecordSource]
GO
