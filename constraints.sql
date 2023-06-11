ALTER TABLE [HH_Customer] ADD  CONSTRAINT [HH_Customer_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
ALTER TABLE [HH_Customer] ADD  CONSTRAINT [DF_HH_Customer_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_Customer] ADD  DEFAULT ((0)) FOR [Inactive]
ALTER TABLE [HH_Customer] ADD  DEFAULT ((0)) FOR [RecordSource]
ALTER TABLE [HH_Customer] ADD  CONSTRAINT [DF_IsheadOfficeZero]  DEFAULT ((0)) FOR [IsHeadOffice]
ALTER TABLE [HH_Customer] ADD  DEFAULT ((0)) FOR [IsPhysicalLocation]
ALTER TABLE [HH_Customer] ADD  DEFAULT ((0)) FOR [ProofOfVisit]

ALTER TABLE [HH_AR_CustomerCategories] ADD  CONSTRAINT [DF_HH_AR_CustomerCategories_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_AR_CustomerCategories] ADD  CONSTRAINT [hh_AR_CustomerCategories_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
ALTER TABLE [HH_AR_CustomerCategories] ADD  DEFAULT ((0)) FOR [RecordSource]

ALTER TABLE [HH_SalesSector] ADD  CONSTRAINT [DF__HH_SalesS__rowgu__694D1E69]  DEFAULT (newsequentialid()) FOR [rowguid]

ALTER TABLE [HH_AR_PaymentTerms] ADD  CONSTRAINT [DF_HH_AR_PaymentTerms_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_AR_PaymentTerms] ADD  CONSTRAINT [DF_HH_AR_PaymentTerms_buid]  DEFAULT ('1') FOR [buid]
ALTER TABLE [HH_AR_PaymentTerms] ADD  DEFAULT ((0)) FOR [MaxOpenInv]
ALTER TABLE [HH_AR_PaymentTerms] ADD  DEFAULT ((0)) FOR [MaxOverdueInv]
ALTER TABLE [HH_AR_PaymentTerms] ADD  DEFAULT ((0)) FOR [RecordSource]
ALTER TABLE [HH_AR_PaymentTerms] ADD  DEFAULT ((0)) FOR [PartialPayment]

ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [HH_Salesman_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_SalesManType]  DEFAULT (2) FOR [SalesManType]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_IsUser]  DEFAULT (0) FOR [IsUser]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PreFix]  DEFAULT (N'espace') FOR [PreFix]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_NextOrderNo]  DEFAULT (0) FOR [NextOrderNo]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_NextVisitNo]  DEFAULT (0) FOR [NextVisitNo]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_NextPaymentNo]  DEFAULT (0) FOR [NextPaymentNo]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Book1Start]  DEFAULT (5) FOR [Book1Start]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Book1End]  DEFAULT (0) FOR [Book1End]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Book2Start]  DEFAULT (0) FOR [Book2Start]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Book2End]  DEFAULT (0) FOR [Book2End]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PayBook1Start]  DEFAULT (0) FOR [PayBook1Start]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PayBook1End]  DEFAULT (0) FOR [PayBook1End]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PayBook2Start]  DEFAULT (0) FOR [PayBook2Start]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_PayBook2End]  DEFAULT (0) FOR [PayBook2End]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_BookSizeMult]  DEFAULT (1) FOR [BookSizeMult]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_Password]  DEFAULT ((123)) FOR [Password]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [NextRequestNo]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [Inactive]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [PreferredLanguage]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [HWsupport]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [NextReturnNo]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [SyncBeforeVisit]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [UploadAfterVisit]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [RecordSource]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [EnableOrderWithoutPOV]
ALTER TABLE [HH_Salesman] ADD  CONSTRAINT [DF_HH_Salesman_DisableDayofvisitedit]  DEFAULT ((0)) FOR [DisableDayofvisitedit]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [ReturnType]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [IsParent]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((1)) FOR [ConsumptionFactor]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [RecQtyCalcType]
ALTER TABLE [HH_Salesman] ADD  DEFAULT ((0)) FOR [CommissionType]

ALTER TABLE [HH_AR_SalesmenCats] ADD  CONSTRAINT [DF_HH_AR_SalesmenCats_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_AR_SalesmenCats] ADD  CONSTRAINT [HH_AR_SalesmenCats_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
ALTER TABLE [HH_AR_SalesmenCats] ADD  DEFAULT ((0)) FOR [RecordSource]

ALTER TABLE [HH_IC_Warehouse] ADD  CONSTRAINT [HH_IC_Warehouse_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
ALTER TABLE [HH_IC_Warehouse] ADD  DEFAULT ((0)) FOR [WarehouseType]
ALTER TABLE [HH_IC_Warehouse] ADD  DEFAULT ((0)) FOR [RecordSource]
ALTER TABLE [HH_IC_Warehouse] ADD  DEFAULT ((0)) FOR [Locked]
ALTER TABLE [HH_IC_Warehouse] ADD  CONSTRAINT [DF__HH_IC_Warehouse_SecUOMNo]  DEFAULT ((0)) FOR [SecUOMNo]
ALTER TABLE [HH_IC_Warehouse] ADD  DEFAULT ((1)) FOR [IsDelivery]
ALTER TABLE [HH_IC_Warehouse] ADD  DEFAULT (newid()) FOR [rowguid]

ALTER TABLE [HH_SalesmanSector] ADD  CONSTRAINT [DF__HH_Salesm__rowgu__6F05F7BF]  DEFAULT (newsequentialid()) FOR [rowguid]

ALTER TABLE [HH_Staff] ADD  DEFAULT ((0)) FOR [RecordSource]
ALTER TABLE [HH_Staff] ADD  DEFAULT (newid()) FOR [rowguid]

ALTER TABLE [HH_Area] ADD  CONSTRAINT [DF_HH_Area_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_Area] ADD  CONSTRAINT [hh_Area_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
ALTER TABLE [HH_Area] ADD  DEFAULT ((0)) FOR [RecordSource]

ALTER TABLE [HH_City] ADD  CONSTRAINT [DF_HH_City_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_City] ADD  CONSTRAINT [hh_City_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
ALTER TABLE [HH_City] ADD  DEFAULT ((0)) FOR [RecordSource]

ALTER TABLE [HH_District] ADD  CONSTRAINT [DF_HH_District_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_District] ADD  CONSTRAINT [hh_District_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
ALTER TABLE [HH_District] ADD  DEFAULT ((0)) FOR [RecordSource]

ALTER TABLE [HH_Region] ADD  CONSTRAINT [DF_HH_Region_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_Region] ADD  CONSTRAINT [HH_Region_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
ALTER TABLE [HH_Region] ADD  DEFAULT ((0)) FOR [RecordSource]

ALTER TABLE [HH_AR_CustomerTypes] ADD  CONSTRAINT [DF_HH_AR_CustomerTypes_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_AR_CustomerTypes] ADD  CONSTRAINT [hh_AR_CustomerTypes_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [buid]
ALTER TABLE [HH_AR_CustomerTypes] ADD  DEFAULT ((0)) FOR [RecordSource]

ALTER TABLE [HH_Branch] ADD  CONSTRAINT [HH_Branch_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
ALTER TABLE [HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_SalesManBookStart]  DEFAULT (0) FOR [SalesManBookStart]
ALTER TABLE [HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_SalesManPayBookStart]  DEFAULT (0) FOR [SalesManPayBookStart]
ALTER TABLE [HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_SalesManBookSize]  DEFAULT (0) FOR [SalesManBookSize]
ALTER TABLE [HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_rowguid]  DEFAULT (newsequentialid()) FOR [rowguid]
ALTER TABLE [HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_InvHeader1]  DEFAULT ('') FOR [InvHeader1]
ALTER TABLE [HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_InvHeader2]  DEFAULT ('') FOR [InvHeader2]
ALTER TABLE [HH_Branch] ADD  CONSTRAINT [DF_HH_Branch_InvFooter]  DEFAULT ('') FOR [InvFooter]
ALTER TABLE [HH_Branch] ADD  DEFAULT ((0)) FOR [RecordSource]
ALTER TABLE [IC_Currency] ADD  DEFAULT (newid()) FOR [rowguid]

ALTER TABLE [HH_SA_BU] ADD  CONSTRAINT [hh_sa_bu_BUID_DEFAULT_CONSTRAINT]  DEFAULT ('1') FOR [BUID]
ALTER TABLE [HH_SA_BU] ADD  DEFAULT ((0)) FOR [HasChildren]
ALTER TABLE [HH_SA_BU] ADD  DEFAULT ((0)) FOR [TypeOfPayment]
ALTER TABLE [HH_SA_BU] ADD  DEFAULT (newid()) FOR [rowguid]

ALTER TABLE [HH_Route] ADD  DEFAULT ((0)) FOR [InActive]

ALTER TABLE [AR_RouteCategoriesGroup] ADD  DEFAULT ((0)) FOR [HasChildren]

ALTER TABLE [HH_RangeSectors] ADD  DEFAULT (newid()) FOR [rowguid]

ALTER TABLE [JPCustomer] ADD  DEFAULT ((0)) FOR [RecordSource]

ALTER TABLE [JPCustomer] ADD  DEFAULT ((0)) FOR [IsPotential]

ALTER TABLE [JPCustomer]  WITH CHECK ADD  CONSTRAINT [FK_JPCustomer_JourneyPlan] FOREIGN KEY([JPID])
REFERENCES [JourneyPlan] ([ID])
ON DELETE CASCADE

ALTER TABLE [JPCustomer] CHECK CONSTRAINT [FK_JPCustomer_JourneyPlan]
ALTER TABLE [JourneyPlan] ADD  CONSTRAINT [DEF_JP_TYPE]  DEFAULT ('Master') FOR [Type]
ALTER TABLE [JourneyPlan] ADD  DEFAULT ((0)) FOR [RecordSource]