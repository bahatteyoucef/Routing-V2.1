-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2023 at 12:44 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `officedi_routing_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `ar_routecategoriesgroup`
--

CREATE TABLE `ar_routecategoriesgroup` (
  `GroupID` varchar(15) NOT NULL DEFAULT '',
  `Description` varchar(50) DEFAULT NULL,
  `DescriptionA` varchar(50) DEFAULT NULL,
  `ParentAccount` varchar(15) DEFAULT NULL,
  `Level` tinyint(3) UNSIGNED DEFAULT NULL,
  `HasChildren` tinyint(3) UNSIGNED DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `BUID` varchar(15) DEFAULT NULL,
  `CommissionSchemeID` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ar_territorymapping`
--

CREATE TABLE `ar_territorymapping` (
  `TerritoryID` varchar(50) NOT NULL DEFAULT '',
  `SalesMan` varchar(50) DEFAULT NULL,
  `SessionDescription` varchar(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hh_area`
--

CREATE TABLE `hh_area` (
  `RegionNo` varchar(15) NOT NULL DEFAULT '',
  `DistrictNo` varchar(30) NOT NULL DEFAULT '',
  `CityNo` varchar(60) NOT NULL DEFAULT '',
  `AreaNo` varchar(60) NOT NULL DEFAULT '',
  `AreaNameE` varchar(25) DEFAULT NULL,
  `AreaNameA` varchar(25) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `buid` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_ar_customercategories`
--

CREATE TABLE `hh_ar_customercategories` (
  `CategoryId` varchar(15) NOT NULL DEFAULT '',
  `Description` varchar(50) DEFAULT NULL,
  `ArabicDescription` varchar(50) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `buid` varchar(15) DEFAULT NULL,
  `DefaultPriceListID` varchar(15) DEFAULT NULL,
  `DefaultPaymentTerms` varchar(15) DEFAULT NULL,
  `DefaultTermsBranch` varchar(15) DEFAULT NULL,
  `DefaultCreditLimit` double DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `RecID` bigint(20) DEFAULT NULL,
  `SalesTypeID` varchar(15) DEFAULT NULL,
  `AllowPriceEdit` tinyint(3) UNSIGNED DEFAULT NULL,
  `EditPriceMinPCT` decimal(4,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_ar_customertypes`
--

CREATE TABLE `hh_ar_customertypes` (
  `TypeId` varchar(15) NOT NULL DEFAULT '',
  `Description` varchar(40) DEFAULT NULL,
  `ArabicDescription` varchar(40) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `buid` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `DisplayOrder` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_ar_paymentterms`
--

CREATE TABLE `hh_ar_paymentterms` (
  `TermsBranch` varchar(15) NOT NULL DEFAULT '',
  `TermsId` varchar(15) NOT NULL DEFAULT '',
  `Description` varchar(50) DEFAULT NULL,
  `ArabicDescription` varchar(50) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `buid` varchar(15) DEFAULT NULL,
  `PaymentDays` int(11) DEFAULT NULL,
  `MaxOpenInv` int(11) DEFAULT NULL,
  `MaxOverdueInv` int(11) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `PartialPayment` tinyint(3) UNSIGNED DEFAULT NULL,
  `DueDateValue` tinyint(3) UNSIGNED DEFAULT NULL,
  `AllowInstallment` tinyint(3) UNSIGNED DEFAULT NULL,
  `MinInstallment` int(11) DEFAULT NULL,
  `MaxInstallment` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_ar_salesmencats`
--

CREATE TABLE `hh_ar_salesmencats` (
  `CategoryId` varchar(15) NOT NULL DEFAULT '',
  `Description` varchar(50) DEFAULT NULL,
  `ArabicDescription` varchar(50) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `buid` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `CommissionSchemeID` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_branch`
--

CREATE TABLE `hh_branch` (
  `BranchNo` varchar(15) NOT NULL DEFAULT '',
  `BranchNameE` varchar(50) DEFAULT NULL,
  `BranchNameA` varchar(50) DEFAULT NULL,
  `Address` longtext DEFAULT NULL,
  `ArabicAddress` longtext DEFAULT NULL,
  `TaxRegCode` varchar(30) DEFAULT NULL,
  `SalesTaxRegCode` varchar(30) DEFAULT NULL,
  `BUID` varchar(15) DEFAULT NULL,
  `SalesManBookStart` int(11) NOT NULL DEFAULT 0,
  `SalesManPayBookStart` int(11) NOT NULL DEFAULT 0,
  `SalesManBookSize` int(11) NOT NULL DEFAULT 0,
  `DefaultWareHouseNo` varchar(15) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `password` varchar(15) DEFAULT NULL,
  `SalesTaxNo` varchar(30) DEFAULT NULL,
  `InvHeader1` varchar(200) NOT NULL DEFAULT '',
  `InvHeader2` varchar(200) NOT NULL DEFAULT '',
  `InvFooter` varchar(200) NOT NULL DEFAULT '',
  `BranchServerURL` varchar(100) DEFAULT NULL,
  `RegionNo` varchar(15) DEFAULT NULL,
  `GLAccount` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `CashJournalNameId` varchar(20) DEFAULT NULL,
  `CheckJournalNameId` varchar(20) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `CurrencyID` varchar(15) DEFAULT NULL,
  `ERPDocType` varchar(15) DEFAULT NULL,
  `CashPaymentMethod` varchar(50) DEFAULT NULL,
  `CheckPaymentMethod` varchar(50) DEFAULT NULL,
  `InventJournalName` varchar(150) DEFAULT NULL,
  `DamagedWarehouse` varchar(15) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `Altitude` double DEFAULT NULL,
  `ErrorRadius` int(11) DEFAULT NULL,
  `ReturnWarehouse` varchar(15) DEFAULT NULL,
  `DefaultVanWarehouse` varchar(15) DEFAULT NULL,
  `PreServiceTimeType` varchar(15) DEFAULT NULL,
  `PostServiceTimeType` varchar(15) DEFAULT NULL,
  `ERPProfitCenter` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_city`
--

CREATE TABLE `hh_city` (
  `RegionNo` varchar(15) NOT NULL DEFAULT '',
  `DistrictNo` varchar(30) NOT NULL DEFAULT '',
  `CITYNO` varchar(60) NOT NULL DEFAULT '',
  `CityNameE` varchar(25) DEFAULT NULL,
  `CityNameA` varchar(25) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `buid` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_customer`
--

CREATE TABLE `hh_customer` (
  `CustomerNo` varchar(15) NOT NULL DEFAULT '',
  `CategoryNo` varchar(15) DEFAULT NULL,
  `BusinessId` varchar(15) DEFAULT NULL,
  `SalesSectorNo` varchar(30) DEFAULT NULL,
  `ClassId` varchar(15) DEFAULT NULL,
  `TermsId` varchar(15) DEFAULT NULL,
  `SalesmanNo` varchar(15) DEFAULT NULL,
  `CustomerNameE` varchar(50) DEFAULT NULL,
  `CustomerNameA` varchar(50) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `AreaNo` varchar(60) DEFAULT NULL,
  `CityNo` varchar(60) DEFAULT NULL,
  `DistrictNo` varchar(30) DEFAULT NULL,
  `RegionNo` varchar(15) DEFAULT NULL,
  `Tel` varchar(50) DEFAULT NULL,
  `StopOrder` tinyint(3) UNSIGNED DEFAULT NULL,
  `BadPayer` tinyint(3) UNSIGNED DEFAULT NULL,
  `StopDate` datetime(3) DEFAULT NULL,
  `ForceCreditLimit` tinyint(3) UNSIGNED DEFAULT NULL,
  `CreditLimit` decimal(20,9) DEFAULT NULL,
  `AllowCheck` tinyint(3) UNSIGNED DEFAULT NULL,
  `AllowDeferred` tinyint(3) UNSIGNED DEFAULT NULL,
  `AllowCash` tinyint(3) UNSIGNED DEFAULT NULL,
  `ContactType` int(11) NOT NULL DEFAULT 0,
  `PriceId` varchar(15) DEFAULT NULL,
  `TaxId` varchar(50) DEFAULT NULL,
  `StopOutLimit` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `UnpaidInvoiceCustomerWarning` tinyint(3) UNSIGNED DEFAULT NULL,
  `CustomerServiceManNo` varchar(15) DEFAULT NULL,
  `CustomerType` varchar(10) DEFAULT NULL,
  `CustomerStatus` int(11) DEFAULT NULL,
  `Mobile` varchar(35) DEFAULT NULL,
  `Balance` double DEFAULT NULL,
  `MerchType` int(11) DEFAULT NULL,
  `Sat` tinyint(4) DEFAULT NULL,
  `Sun` tinyint(4) DEFAULT NULL,
  `Mon` tinyint(4) DEFAULT NULL,
  `Tue` tinyint(4) DEFAULT NULL,
  `Wed` tinyint(4) DEFAULT NULL,
  `Thu` tinyint(4) DEFAULT NULL,
  `Fri` tinyint(4) DEFAULT NULL,
  `TargetNPS` int(11) DEFAULT NULL,
  `ActualNPS` int(11) DEFAULT NULL,
  `ToVisit` tinyint(4) DEFAULT NULL,
  `BUID` varchar(15) DEFAULT NULL,
  `id_upload_client` int(11) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `today` int(11) DEFAULT NULL,
  `TermsBranch` varchar(15) DEFAULT NULL,
  `VisitOrder` int(11) DEFAULT NULL,
  `ActualAvg` int(11) DEFAULT NULL,
  `TargetAvg` int(11) DEFAULT NULL,
  `CashDiscountID` varchar(15) DEFAULT NULL,
  `VisitFrequency` int(11) DEFAULT NULL,
  `Latitude` decimal(24,15) DEFAULT NULL,
  `Longitude` decimal(24,15) DEFAULT NULL,
  `Altitude` decimal(24,15) DEFAULT NULL,
  `ErrorRadius` int(11) DEFAULT NULL,
  `InvoiceAccount` varchar(15) DEFAULT NULL,
  `RouteID` varchar(60) DEFAULT NULL,
  `Inactive` tinyint(3) UNSIGNED DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `PaymentMethod` tinyint(3) UNSIGNED DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `AccountNumber` varchar(30) DEFAULT NULL,
  `RequiresManualInvNo` tinyint(3) UNSIGNED DEFAULT NULL,
  `OrderCeiling` double NOT NULL DEFAULT 0,
  `AddressA` varchar(100) DEFAULT NULL,
  `AddAddressE` varchar(50) DEFAULT NULL,
  `AddAddressA` varchar(50) DEFAULT NULL,
  `ElectricityNo` varchar(35) DEFAULT NULL,
  `LicenseNo` varchar(35) DEFAULT NULL,
  `GISCode` varchar(30) DEFAULT NULL,
  `AllowCreditCard` tinyint(3) UNSIGNED DEFAULT NULL,
  `ConfirmedOrders` double DEFAULT NULL,
  `ProductRangeID` varchar(15) DEFAULT NULL,
  `HeadOfficeID` varchar(15) DEFAULT NULL,
  `IsHeadOffice` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `ReturnWithoutInvoice` tinyint(3) UNSIGNED DEFAULT NULL,
  `HasCreditControlArea` tinyint(3) UNSIGNED DEFAULT NULL,
  `PotCustomerRef` varchar(15) DEFAULT NULL,
  `MandatoryPhoto` tinyint(3) UNSIGNED DEFAULT NULL,
  `PointsBalance` int(11) DEFAULT NULL,
  `NoOfAllowedRedeemPacks` int(11) DEFAULT NULL,
  `ServicePatternSet` varchar(50) DEFAULT NULL,
  `IsPhysicalLocation` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `CutomerPhysicalLocation` varchar(15) DEFAULT NULL,
  `PostalCode` varchar(50) DEFAULT NULL,
  `ProofOfVisit` tinyint(3) UNSIGNED DEFAULT NULL,
  `LegacyCustomerName` varchar(50) DEFAULT NULL,
  `LegacyCustomerAddress` varchar(100) DEFAULT NULL,
  `IsPotential` tinyint(3) UNSIGNED DEFAULT NULL,
  `RecommendedPriceID` varchar(15) DEFAULT NULL,
  `WindowTypeId` varchar(15) DEFAULT NULL,
  `OpenTime` datetime(3) DEFAULT NULL,
  `CloseTime` datetime(3) DEFAULT NULL,
  `OpenTimeTW1` datetime(3) DEFAULT NULL,
  `CloseTimeTW1` datetime(3) DEFAULT NULL,
  `OpenTimeTW2` datetime(3) DEFAULT NULL,
  `CloseTimeTW2` datetime(3) DEFAULT NULL,
  `ServiceTimeTypeId` varchar(15) DEFAULT NULL,
  `AllowTempCredit` tinyint(3) UNSIGNED DEFAULT NULL,
  `DefaultTerritory` varchar(15) DEFAULT NULL,
  `StopReasonID` varchar(40) DEFAULT NULL,
  `StopComments` varchar(250) DEFAULT NULL,
  `StopUser` varchar(15) DEFAULT NULL,
  `StopDateTime` datetime(3) DEFAULT NULL,
  `AllowEPayment` tinyint(3) UNSIGNED DEFAULT NULL,
  `PreventSalesmanfromCollectingOutStandingInvoices` tinyint(3) UNSIGNED DEFAULT NULL,
  `AllowInstallment` tinyint(3) UNSIGNED DEFAULT NULL,
  `AllowPriceEdit` tinyint(3) UNSIGNED DEFAULT NULL,
  `MinPriceID` varchar(15) DEFAULT NULL,
  `AllowBankTransfer` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_customersalestypes`
--

CREATE TABLE `hh_customersalestypes` (
  `SalesTypeID` varchar(30) NOT NULL DEFAULT '',
  `Description` varchar(50) DEFAULT NULL,
  `DescriptionAR` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_district`
--

CREATE TABLE `hh_district` (
  `RegionNo` varchar(15) NOT NULL DEFAULT '',
  `DistrictNo` varchar(30) NOT NULL DEFAULT '',
  `DistrictNameE` varchar(25) DEFAULT NULL,
  `DistrictNameA` varchar(25) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `buid` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_ic_warehouse`
--

CREATE TABLE `hh_ic_warehouse` (
  `WarehouseID` varchar(15) NOT NULL DEFAULT '',
  `L1Description` varchar(50) DEFAULT NULL,
  `L2Description` varchar(50) DEFAULT NULL,
  `Category` varchar(15) DEFAULT NULL,
  `BUID` varchar(15) DEFAULT NULL,
  `WarehouseType` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `TransWarehouseID` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `PalletNo` double DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `DriverID` varchar(15) DEFAULT NULL,
  `StdPalletNo` double DEFAULT NULL,
  `VanCeiling` double DEFAULT NULL,
  `Locked` tinyint(3) UNSIGNED DEFAULT NULL,
  `LockOwner` varchar(50) DEFAULT NULL,
  `SecUOMNo` double NOT NULL DEFAULT 0,
  `IsDelivery` tinyint(3) UNSIGNED DEFAULT NULL,
  `IntegrationType` varchar(15) DEFAULT NULL,
  `Location` varchar(50) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `Altitude` double DEFAULT NULL,
  `ErrorRadius` int(11) DEFAULT NULL,
  `ERPOrderType` varchar(50) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_rangesectors`
--

CREATE TABLE `hh_rangesectors` (
  `RangeID` varchar(15) NOT NULL DEFAULT '',
  `SectorID` varchar(15) NOT NULL DEFAULT '',
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_region`
--

CREATE TABLE `hh_region` (
  `RegionNo` varchar(15) NOT NULL DEFAULT '',
  `RegionNameE` varchar(25) DEFAULT NULL,
  `RegionNameA` varchar(25) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `buid` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_route`
--

CREATE TABLE `hh_route` (
  `RouteID` varchar(60) NOT NULL DEFAULT '',
  `RouteNameE` varchar(100) DEFAULT NULL,
  `RouteNameA` varchar(100) DEFAULT NULL,
  `BUID` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `Type` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `RouteCategoryID` varchar(15) DEFAULT NULL,
  `RegionNo` varchar(15) DEFAULT NULL,
  `DistrictNo` varchar(30) DEFAULT NULL,
  `CityNo` varchar(60) DEFAULT NULL,
  `AreaNo` varchar(60) DEFAULT NULL,
  `InActive` tinyint(3) UNSIGNED DEFAULT NULL,
  `RouteChannel` varchar(60) DEFAULT NULL,
  `RouteGTM` varchar(60) DEFAULT NULL,
  `CommissionSchemeID` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_salesman`
--

CREATE TABLE `hh_salesman` (
  `SalesmanNo` varchar(15) NOT NULL DEFAULT '',
  `SalesmanNameE` varchar(50) DEFAULT NULL,
  `SalesmanNameA` varchar(50) DEFAULT NULL,
  `CategoryId` varchar(15) DEFAULT NULL,
  `BUID` varchar(15) DEFAULT NULL,
  `BranchNo` varchar(15) DEFAULT NULL,
  `SalesManType` int(11) DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `Tel` varchar(50) DEFAULT NULL,
  `Mobile` varchar(50) DEFAULT NULL,
  `HashPass` varchar(50) DEFAULT NULL,
  `IsUser` tinyint(4) NOT NULL DEFAULT 0,
  `outOfRouteLimit` int(11) DEFAULT NULL,
  `PreFix` varchar(10) NOT NULL DEFAULT '0',
  `NextOrderNo` int(11) NOT NULL DEFAULT 0,
  `NextVisitNo` int(11) NOT NULL DEFAULT 0,
  `NextPaymentNo` int(11) NOT NULL DEFAULT 0,
  `Book1Start` int(11) NOT NULL DEFAULT 0,
  `Book1End` int(11) NOT NULL DEFAULT 0,
  `Book2Start` int(11) DEFAULT NULL,
  `Book2End` int(11) NOT NULL DEFAULT 0,
  `PayBook1Start` int(11) DEFAULT 0,
  `PayBook1End` int(11) NOT NULL DEFAULT 0,
  `PayBook2Start` int(11) NOT NULL DEFAULT 0,
  `PayBook2End` int(11) NOT NULL DEFAULT 0,
  `BookSizeMult` int(11) NOT NULL DEFAULT 0,
  `Password` varchar(15) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '0',
  `CurrentOutOfRoute` int(11) DEFAULT NULL,
  `WareHouse` varchar(15) DEFAULT NULL,
  `AssignedDriverID` varchar(15) DEFAULT NULL,
  `OutofOrderLimit` int(11) DEFAULT NULL,
  `SectorID` varchar(15) DEFAULT NULL,
  `UseNewPrintFW` tinyint(3) UNSIGNED DEFAULT NULL,
  `GPSMinTrackingDist` int(11) DEFAULT NULL,
  `GPSMinTrackingTime` int(11) DEFAULT NULL,
  `ProofOfVisit` tinyint(3) UNSIGNED DEFAULT NULL,
  `MaxVisitsWithoutProof` int(11) DEFAULT NULL,
  `Permission` tinyint(3) UNSIGNED DEFAULT NULL,
  `NextCustLocUpdateNo` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `NextRequestNo` int(11) DEFAULT NULL,
  `Balance` double DEFAULT NULL,
  `CreditLimit` double DEFAULT NULL,
  `Inactive` tinyint(3) UNSIGNED DEFAULT NULL,
  `PreferredLanguage` tinyint(3) UNSIGNED DEFAULT NULL,
  `HWsupport` tinyint(3) UNSIGNED DEFAULT NULL,
  `RebateDocumentType` varchar(15) DEFAULT NULL,
  `NextReturnNo` int(11) DEFAULT NULL,
  `EncPassword` varchar(20) DEFAULT NULL,
  `SyncBeforeVisit` tinyint(3) UNSIGNED DEFAULT NULL,
  `UploadAfterVisit` tinyint(3) UNSIGNED DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `JPCustCount` int(11) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `Driver` varchar(15) DEFAULT NULL,
  `Helper1` varchar(15) DEFAULT NULL,
  `Helper2` varchar(15) DEFAULT NULL,
  `Helper3` varchar(15) DEFAULT NULL,
  `DefaultCustomerNo` varchar(15) DEFAULT NULL,
  `ERP_Reference` varchar(30) DEFAULT NULL,
  `EnableOrderWithoutPOV` tinyint(3) UNSIGNED DEFAULT NULL,
  `DefaultMSL` varchar(15) DEFAULT NULL,
  `DisableDayofvisitedit` tinyint(3) UNSIGNED DEFAULT NULL,
  `SMDistanceRange` int(11) DEFAULT NULL,
  `RouteID` varchar(60) DEFAULT NULL,
  `AllowCreditOverride` tinyint(3) UNSIGNED DEFAULT NULL,
  `CalendarID` varchar(15) DEFAULT NULL,
  `OverDueInvoicesAmountLimit` double DEFAULT NULL,
  `OverDueInvoicesCountLimit` double DEFAULT NULL,
  `OpenInvoicesAmountLimit` double DEFAULT NULL,
  `OpenInvoicesCountLimit` double DEFAULT NULL,
  `TemporaryCredit` double DEFAULT NULL,
  `TemporaryCreditBalance` double DEFAULT NULL,
  `SMSTemplateTypeID` varchar(15) DEFAULT NULL,
  `preventcollect` tinyint(3) UNSIGNED DEFAULT NULL,
  `CommissionSchemeID` varchar(15) DEFAULT NULL,
  `LastPasswordRenew` datetime(3) DEFAULT NULL,
  `ReturnType` tinyint(3) UNSIGNED DEFAULT NULL,
  `TransferPriceID` varchar(15) DEFAULT NULL,
  `EnablePrePayment` tinyint(3) UNSIGNED DEFAULT NULL,
  `CreditCardAccount` varchar(15) DEFAULT NULL,
  `CashJournalNo` varchar(50) DEFAULT NULL,
  `ChequeJournalNo` varchar(50) DEFAULT NULL,
  `LanguageID` varchar(50) DEFAULT NULL,
  `OrderTakerSuggestedValue` varchar(20) DEFAULT NULL,
  `ClassID` varchar(30) DEFAULT NULL,
  `DamagedWarehouse` varchar(15) DEFAULT NULL,
  `EnableManualDiscountOnOrderLine` int(11) DEFAULT NULL,
  `ParentEmployee` varchar(15) DEFAULT NULL,
  `OverduePendingPaymentsDays` int(11) DEFAULT NULL,
  `IsParent` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `LoadingLimit` double DEFAULT NULL,
  `ParentSalesmanID` varchar(15) DEFAULT NULL,
  `UploadTruckNoToERP` tinyint(3) UNSIGNED DEFAULT NULL,
  `ConsumptionFactor` double NOT NULL DEFAULT 0,
  `RecQtyCalcType` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `AllowOnlineReturn` int(11) DEFAULT NULL,
  `ReturnWarehouse` varchar(15) DEFAULT NULL,
  `VanIndicator` tinyint(3) UNSIGNED DEFAULT NULL,
  `mandatoryStockCheckPhoto` tinyint(3) UNSIGNED DEFAULT NULL,
  `IgnoreCheckingPendingTransactions` tinyint(3) UNSIGNED DEFAULT NULL,
  `ReplenishmentTemplateID` varchar(15) DEFAULT NULL,
  `EnableProforma` tinyint(3) UNSIGNED DEFAULT NULL,
  `CreditCardReceiptMethod` varchar(15) DEFAULT NULL,
  `ChequeReceiptMethod` varchar(15) DEFAULT NULL,
  `CashReceiptMethod` varchar(15) DEFAULT NULL,
  `DefaultTerritory` varchar(15) DEFAULT NULL,
  `SalesbuzzMobileClient` tinyint(3) UNSIGNED DEFAULT NULL,
  `DisableLoadWithBalance` tinyint(3) UNSIGNED DEFAULT NULL,
  `StopReasonID` varchar(15) DEFAULT NULL,
  `StopComments` varchar(250) DEFAULT NULL,
  `StopUser` varchar(15) DEFAULT NULL,
  `StopDateTime` datetime(3) DEFAULT NULL,
  `AllowOnlineConfirm` tinyint(3) UNSIGNED DEFAULT NULL,
  `AllowOnlineEdit` tinyint(3) UNSIGNED DEFAULT NULL,
  `AllowOnlineClose` tinyint(3) UNSIGNED DEFAULT NULL,
  `MaxOfflineInvoices` tinyint(3) UNSIGNED DEFAULT NULL,
  `CommissionType` tinyint(3) UNSIGNED DEFAULT NULL,
  `CommissionValue` double DEFAULT NULL,
  `RouteOrigin` tinyint(3) UNSIGNED DEFAULT NULL,
  `EnableAutoArrive` tinyint(3) UNSIGNED DEFAULT NULL,
  `Mode` tinyint(3) UNSIGNED DEFAULT NULL,
  `AllowPriceEdit` tinyint(3) UNSIGNED DEFAULT NULL,
  `TransferReceiptMethod` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_salesmansector`
--

CREATE TABLE `hh_salesmansector` (
  `SalesmanNo` varchar(15) NOT NULL DEFAULT '',
  `SectorId` varchar(15) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_salessector`
--

CREATE TABLE `hh_salessector` (
  `SectorID` varchar(15) NOT NULL DEFAULT '',
  `SectorName` varchar(50) DEFAULT NULL,
  `SectorNameAr` varchar(50) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `TransferPriceID` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_sa_bu`
--

CREATE TABLE `hh_sa_bu` (
  `BUID` varchar(15) NOT NULL DEFAULT '',
  `Description` varchar(50) DEFAULT NULL,
  `DescriptionA` varchar(50) DEFAULT NULL,
  `ParentBU` varchar(15) DEFAULT NULL,
  `Level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `HasChildren` tinyint(3) UNSIGNED DEFAULT NULL,
  `ShortCode` varchar(5) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `OrganizationType` tinyint(3) UNSIGNED DEFAULT NULL,
  `ERPOrganizationID` varchar(15) DEFAULT NULL,
  `MemoLineId` varchar(15) DEFAULT NULL,
  `ERPDistChannelID` varchar(35) DEFAULT NULL,
  `ERPSalesDivisonID` varchar(35) DEFAULT NULL,
  `TypeOfPayment` tinyint(3) UNSIGNED DEFAULT NULL,
  `CompanyGlAccount` varchar(25) DEFAULT NULL,
  `CurrencyID` varchar(15) DEFAULT NULL,
  `SAPGovernorate` varchar(30) DEFAULT NULL,
  `SAPplant` varchar(30) DEFAULT NULL,
  `SalesTypeID` varchar(15) DEFAULT NULL,
  `SAPSalesOffice` varchar(15) DEFAULT NULL,
  `SAPSalesdistrict` varchar(30) DEFAULT NULL,
  `SAPCompanyCode` varchar(30) DEFAULT NULL,
  `SAPCreditControlArea` varchar(15) DEFAULT NULL,
  `SAPCreditSegment` varchar(15) DEFAULT NULL,
  `SAPSalesOrg` varchar(15) DEFAULT NULL,
  `DefaultWarehouse` varchar(15) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT '',
  `SAPShortCode` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_sa_bulevels`
--

CREATE TABLE `hh_sa_bulevels` (
  `LevelNo` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `Description` varchar(50) DEFAULT NULL,
  `DescriptionA` varchar(50) DEFAULT NULL,
  `LevelLength` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hh_staff`
--

CREATE TABLE `hh_staff` (
  `StaffID` varchar(15) NOT NULL DEFAULT '',
  `Name` varchar(50) DEFAULT NULL,
  `NameA` varchar(50) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `BUID` varchar(15) NOT NULL DEFAULT '',
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `AccountNumber` varchar(30) DEFAULT NULL,
  `DefaultCustomerNo` varchar(15) DEFAULT NULL,
  `CreditLimit` double DEFAULT NULL,
  `OverDueInvoicesAmountLimit` double DEFAULT NULL,
  `OverDueInvoicesCountLimit` double DEFAULT NULL,
  `OpenInvoicesAmountLimit` double DEFAULT NULL,
  `OpenInvoicesCountLimit` double DEFAULT NULL,
  `Inactive` tinyint(3) UNSIGNED DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ic_currency`
--

CREATE TABLE `ic_currency` (
  `CurrencyNo` varchar(15) NOT NULL DEFAULT '',
  `Description` varchar(50) DEFAULT NULL,
  `DescriptionA` varchar(50) DEFAULT NULL,
  `Fraction` varchar(50) DEFAULT NULL,
  `Createdby` varchar(20) DEFAULT NULL,
  `ModifiedBy` varchar(20) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `FractionAr` varchar(50) DEFAULT NULL,
  `rowguid` char(36) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `journeyplan`
--

CREATE TABLE `journeyplan` (
  `ID` int(11) NOT NULL,
  `AssignedTO` varchar(15) DEFAULT NULL,
  `BelongsTo` varchar(15) DEFAULT NULL,
  `JPlan` varchar(25) NOT NULL DEFAULT '',
  `WareHouse` varchar(15) DEFAULT NULL,
  `CityNo` varchar(60) DEFAULT NULL,
  `DistrictNo` varchar(30) DEFAULT NULL,
  `AreaNo` varchar(60) DEFAULT NULL,
  `Type` varchar(15) DEFAULT NULL,
  `parent_id` varchar(15) DEFAULT NULL,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `DescriptionE` varchar(50) DEFAULT NULL,
  `DescriptionAr` varchar(50) DEFAULT NULL,
  `TerritoryNo` varchar(255) DEFAULT NULL,
  `BUID` varchar(255) DEFAULT NULL,
  `CustomerCategory` varchar(255) DEFAULT NULL,
  `SalesmanType` int(11) DEFAULT NULL,
  `RouteID` varchar(255) DEFAULT NULL,
  `EffectiveDate` datetime(3) DEFAULT NULL,
  `IsActive` tinyint(3) UNSIGNED DEFAULT NULL,
  `EndDate` datetime(3) DEFAULT NULL,
  `OrderRouteAssignment` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `jpcustomer`
--

CREATE TABLE `jpcustomer` (
  `CustomerID` varchar(15) NOT NULL DEFAULT '',
  `JPID` int(11) NOT NULL DEFAULT 0,
  `Frequency` tinyint(3) UNSIGNED DEFAULT NULL,
  `StartWeek` tinyint(3) UNSIGNED DEFAULT NULL,
  `sat` tinyint(3) UNSIGNED DEFAULT NULL,
  `sun` tinyint(3) UNSIGNED DEFAULT NULL,
  `mon` tinyint(3) UNSIGNED DEFAULT NULL,
  `tue` tinyint(3) UNSIGNED DEFAULT NULL,
  `wed` tinyint(3) UNSIGNED DEFAULT NULL,
  `thu` tinyint(3) UNSIGNED DEFAULT NULL,
  `fri` tinyint(3) UNSIGNED DEFAULT NULL,
  `VisitOrder` int(11) NOT NULL DEFAULT 1,
  `ModifiedOn` datetime(3) DEFAULT NULL,
  `ModifiedBy` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime(3) DEFAULT NULL,
  `Createdby` varchar(15) DEFAULT NULL,
  `RecordSource` tinyint(3) UNSIGNED DEFAULT NULL,
  `IsPotential` int(11) NOT NULL DEFAULT 0,
  `UpdateGPSLocation` int(11) DEFAULT NULL,
  `TerritoryNo` varchar(15) DEFAULT NULL,
  `EffectiveDate` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('09c6b6e342a0a2b860d107b28cdf47b24b36647c1e1fe586e74267e91ef3e260d801d152b24ac5e2', 1, 1, 'authToken', '[]', 1, '2022-12-17 21:26:02', '2022-12-17 21:28:21', '2023-12-17 22:26:02'),
('0b3672712e950f92f0b52d95c554d7e5ceaf755c6708d7ebf3168e6bfd33e05c539ba0bd2e2c00e9', 1, 1, 'authToken', '[]', 1, '2022-12-16 21:41:14', '2022-12-16 21:41:45', '2023-12-16 22:41:14'),
('0ccc2ccc55f10a51a712f3dfa9168ecc1f0c9c813a5e6d4bcd44b763f05ea313ae6ba4952024b5e2', 1, 1, 'authToken', '[]', 1, '2022-12-16 23:41:50', '2022-12-17 00:05:53', '2023-12-17 00:41:50'),
('1195baf09268c444f23eee8ef1b99bb04fad2bc59bf597dc2693358d4dff9090b45b78465b44a021', 1, 1, 'authToken', '[]', 1, '2022-12-09 21:13:52', '2022-12-09 21:14:08', '2023-12-09 22:13:52'),
('11b4082aa97bcdb034ff5e3d250ac17a9df1f2623cf7a8848dbc69011d39d85369ca142c6ff7f6fa', 1, 1, 'authToken', '[]', 1, '2022-12-16 23:41:03', '2022-12-16 23:41:45', '2023-12-17 00:41:03'),
('1356e3550ddb8ac97e38230da61e4425ca0f1576b6a1c4589789918ff45792e26ad55175a39ce693', 1, 1, 'authToken', '[]', 0, '2022-12-18 18:38:54', '2022-12-18 18:38:54', '2023-12-18 19:38:54'),
('136a4dc2445b436c181a8046a4b7fb0b30fc310e0404857f78692b2c473e086ab9ddda0f46228f0f', 1, 1, 'authToken', '[]', 1, '2022-12-11 20:22:07', '2022-12-11 20:26:05', '2023-12-11 21:22:07'),
('19a901f90b35a8ac33d5d2b6a1488b65b650cbe4e8ba0cf96dad9f1db9961f1a5d856d26abff700b', 1, 1, 'authToken', '[]', 1, '2022-12-11 20:00:20', '2022-12-11 20:00:34', '2023-12-11 21:00:20'),
('1be575510a8d4ffa94852a4508b11315c42c78ba9286320537f245fa493c9ff89cd1a74c1c3a4128', 1, 1, 'authToken', '[]', 0, '2022-12-10 11:59:04', '2022-12-10 11:59:04', '2023-12-10 12:59:04'),
('1e759bcedd04449858bcadb093822b5ceabcaa6a43f5225ce715446c4f485bcce4e7b829511f9c06', 1, 1, 'authToken', '[]', 1, '2022-12-17 00:05:58', '2022-12-17 00:06:44', '2023-12-17 01:05:58'),
('1f324d3d31f11c2f97c71c6e71503c34a3997321574719329a5371f5d94a5104c17ef6ca5868838a', 1, 1, 'authToken', '[]', 0, '2022-12-09 21:05:18', '2022-12-09 21:05:18', '2023-12-09 22:05:18'),
('287e4f0297a1cedbafd5ba09ed367a72ed3dbb7b94700ba3f6f8c59595b36011553bbe8892c6ff05', 1, 1, 'authToken', '[]', 0, '2023-03-02 09:38:04', '2023-03-02 09:38:05', '2024-03-02 10:38:04'),
('2c4c485cc34469c39c3dfc20f9859f13649e2b06c1d3c7568963de26c7caa654482b00ce584d262b', 1, 1, 'authToken', '[]', 0, '2022-12-09 21:07:06', '2022-12-09 21:07:06', '2023-12-09 22:07:06'),
('2d1ed7f4d06dcc6f39b9b39be9923d9f5b3eeb83a2d7806fa9ef96ef1ad674b53dcc91b431cdf10e', 1, 1, 'authToken', '[]', 1, '2022-12-11 22:38:00', '2022-12-11 22:42:50', '2023-12-11 23:38:00'),
('2f0c7a2583410834f43a5606215670bfd4cf76946952a54fc5f1a9630e8ff1193e4c2eb510c266d2', 1, 1, 'authToken', '[]', 0, '2022-12-09 21:21:50', '2022-12-09 21:21:50', '2023-12-09 22:21:50'),
('30888efa329d4d1d1396b34e54c500669831b4a2d36410be4b6e4be182288622e88007e510fba151', 1, 1, 'authToken', '[]', 1, '2022-12-17 20:55:04', '2022-12-17 20:56:59', '2023-12-17 21:55:04'),
('36601ed94f5daa811f391ec93cee5fc7ddd51100f3f7ae548cbb947042b4b79559ff967ad7211c44', 1, 1, 'authToken', '[]', 0, '2023-03-02 11:45:56', '2023-03-02 11:45:56', '2024-03-02 12:45:56'),
('36b13c40631f3ff86dc28c95a0182eb4efaf80c5e89780add57eb58e37f13dba21f023346d211ece', 1, 1, 'authToken', '[]', 0, '2022-12-10 11:56:44', '2022-12-10 11:56:45', '2023-12-10 12:56:44'),
('37283f37783499c55fa4b35fc0e81c9d10e78097ef2be6129e9a1412be908a23406742f890a9dd96', 1, 1, 'authToken', '[]', 1, '2022-12-16 21:38:15', '2022-12-16 21:41:01', '2023-12-16 22:38:15'),
('3c821047f9baf951a9f0e23a78e1d9d329bc7696340e11bdd65a53810e4932be75c1f0a1d004138e', 1, 1, 'authToken', '[]', 0, '2022-12-11 19:07:40', '2022-12-11 19:07:40', '2023-12-11 20:07:40'),
('3dfd9cdff83c3eabb5f2998a7a650280de182ea14586c21523866b37d3543bfc80cac62f81c862db', 1, 1, 'authToken', '[]', 1, '2022-12-11 21:03:07', '2022-12-11 21:03:15', '2023-12-11 22:03:07'),
('3f4a1bc100607eebe85e1b6e730cb17a3c34dfc00dd2344cfa473655c6db2f8f0560d313e9c9bab5', 1, 1, 'authToken', '[]', 0, '2023-03-02 20:38:40', '2023-03-02 20:38:41', '2024-03-02 21:38:40'),
('404c3c16cac710e23824d9b996a7bd5c5c5459e9570031545c299e1aa625c527d5b143adf650e04b', 1, 1, 'authToken', '[]', 0, '2022-12-11 18:50:22', '2022-12-11 18:50:22', '2023-12-11 19:50:22'),
('406fa597f22e27425e88191b5335873b6be1e5cf9aa09fba9a3ae197d523790adee87232b83788e0', 1, 1, 'authToken', '[]', 0, '2022-12-11 19:51:11', '2022-12-11 19:51:11', '2023-12-11 20:51:11'),
('413bc4db7b9c1bb0ecf6e2513a76c8e314f3666cc8f049dc2d7aad669e2fb1b8b6021a2b3cd708e0', 1, 1, 'authToken', '[]', 0, '2022-12-11 19:54:37', '2022-12-11 19:54:37', '2023-12-11 20:54:37'),
('4162d4c4d45f2e824d0c1c0c282b9dcbd07ffd91ad92cb98ed1d54800c5c2b4d86b9660fe8a08b28', 1, 1, 'authToken', '[]', 1, '2022-12-18 13:09:16', '2022-12-18 18:35:05', '2023-12-18 14:09:16'),
('430f8eeacaaa49877fc7a4e619fa923992216267d4bc061fce40ace175502a071cdc198b70184404', 1, 1, 'authToken', '[]', 1, '2022-12-17 19:56:14', '2022-12-17 20:04:23', '2023-12-17 20:56:14'),
('4567d4e7ee3db09c203995202b2ae917e0394cb9882e4ba9c3627bf8cdc48e8c96d137dcb2ec048b', 1, 1, 'authToken', '[]', 1, '2022-12-17 00:10:40', '2022-12-17 00:11:56', '2023-12-17 01:10:40'),
('4b9f8e70bc119ca1a606291efd4ee2b20a5b642c6f3c1d6ce65f254586b33bf4c72b4f6bbb30c364', 1, 1, 'authToken', '[]', 0, '2022-12-11 18:55:23', '2022-12-11 18:55:23', '2023-12-11 19:55:23'),
('4ca9092bcbcdb24fbdad4819bf06ac8efa937e637a5928b19cbeb7ed8bda672a92feb56fea905159', 1, 1, 'authToken', '[]', 0, '2022-12-09 19:25:04', '2022-12-09 19:25:04', '2023-12-09 20:25:04'),
('4d0876c1e9e21ebcfafa054cf140f48bd23ad7d003d956515aef41bf97a714ed943ed9259eb88641', 1, 1, 'authToken', '[]', 1, '2022-12-09 20:38:30', '2022-12-09 20:49:52', '2023-12-09 21:38:30'),
('4d867610725d7920f2fbab205b2868e1918c37a21d067b0803430fc87935404eefc7ec8da3831f81', 1, 1, 'authToken', '[]', 0, '2023-03-01 14:01:57', '2023-03-01 14:01:58', '2024-03-01 15:01:57'),
('4d9c739027fd6794ffc624318d275be9de33ca2af1cebde83783e2fb9917140ea4df548094a45b5a', 1, 1, 'authToken', '[]', 1, '2022-12-16 23:07:06', '2022-12-16 23:07:39', '2023-12-17 00:07:06'),
('4e082f26583176cf2205524982b300594244713cff41669bd69b3126c74100453029845ac106e828', 1, 1, 'authToken', '[]', 1, '2022-12-17 00:07:21', '2022-12-17 00:08:33', '2023-12-17 01:07:21'),
('5001752ba819aba033dd61183eeb90f273e4120f27e2a42a5c6bcf1db9b57e63d78cc62448e88620', 1, 1, 'authToken', '[]', 0, '2022-12-11 18:52:38', '2022-12-11 18:52:38', '2023-12-11 19:52:38'),
('5656757f487cc0097c7abaa0dd39635955614b7837ab9e1fbb00fcf597874836dbe3452a57006b17', 1, 1, 'authToken', '[]', 0, '2022-12-10 12:21:26', '2022-12-10 12:21:26', '2023-12-10 13:21:26'),
('57f919a25948e0117e3b0a447a9a40468733e623801bf03979ff9e13ad5355d5d0ff1782ad1a6008', 1, 1, 'authToken', '[]', 0, '2022-12-11 18:59:43', '2022-12-11 18:59:43', '2023-12-11 19:59:43'),
('584a40ab73e370779e23c8e63c221d064fc9a53d175c8fa6a05d55638c72539b696e0b34d9a7d098', 1, 1, 'authToken', '[]', 1, '2022-12-17 20:57:01', '2022-12-17 21:04:46', '2023-12-17 21:57:01'),
('5b37f7f15d556cd2dc63b7d27cae0595ee358072d6134e4d36b55b5ef050d405aa3c5e544de53c48', 1, 1, 'authToken', '[]', 0, '2022-12-11 19:48:51', '2022-12-11 19:48:51', '2023-12-11 20:48:51'),
('5df36bf47a57166d3be782cdae8c0a0ac0d2adc2c2d08528c15b1849efd555de7810fbcca411b547', 1, 1, 'authToken', '[]', 1, '2022-12-17 00:11:59', '2022-12-17 19:35:16', '2023-12-17 01:11:59'),
('5fe23e6d75c6c05863b1cfd33f9ccad34b134518e83d860acceed18d39d34bec9320fd8f9f5bd8e1', 1, 1, 'authToken', '[]', 1, '2022-12-11 20:26:31', '2022-12-11 21:02:51', '2023-12-11 21:26:31'),
('61c303582171c23595a53f941c8d77a66e90920e6784e77dec5e155c33a08f1d28f597a22ea2cc32', 1, 1, 'authToken', '[]', 1, '2022-12-09 21:20:29', '2022-12-09 21:20:38', '2023-12-09 22:20:29'),
('67439c0f9b60792f4661b3769a859d950955e5d241358179e6dec575bd93f4e734da6aa3ad91a679', 1, 1, 'authToken', '[]', 0, '2022-12-11 20:01:48', '2022-12-11 20:01:48', '2023-12-11 21:01:48'),
('6758d09f08a87d4e63ab44d1bb88eef9e988d25c44c9b8adc705e99deaf3fb879b42554ded177f46', 1, 1, 'authToken', '[]', 1, '2022-12-18 18:38:14', '2022-12-18 18:38:32', '2023-12-18 19:38:14'),
('67e5ca49f16a1bc41624a06aeef52a915ae8bc5cdc97f9f46465f290eeb412cee2c0edb0f9686fc8', 1, 1, 'authToken', '[]', 0, '2022-12-09 20:17:58', '2022-12-09 20:17:58', '2023-12-09 21:17:58'),
('6be1a1f2529efa32006c0c99626a01f0cfcf001a0c45e5ca3128b0887c3b3a8066bfb4f6b05c78d4', 1, 1, 'authToken', '[]', 0, '2022-12-11 19:54:25', '2022-12-11 19:54:25', '2023-12-11 20:54:25'),
('70143423b354a47b9d08517364d26b24a5c694fd2094df697b341aa8dcafb494facd03207c911e1b', 1, 1, 'authToken', '[]', 1, '2022-12-16 23:20:05', '2022-12-16 23:39:48', '2023-12-17 00:20:05'),
('72284514b3d24906d8832f991fce058d122c32fca59ba69d061c8940d8f71e7e1ce2addb1c9b3818', 1, 1, 'authToken', '[]', 0, '2023-03-01 20:39:14', '2023-03-01 20:39:14', '2024-03-01 21:39:14'),
('781cbef005660f2618d513ce13e3bd40a91bcfb552156e3b391aa30afe1d6af2a88dbad03aec8076', 1, 1, 'authToken', '[]', 1, '2022-12-11 18:50:07', '2022-12-11 18:50:10', '2023-12-11 19:50:07'),
('7d361690d64fedd5a1c9adaa283abcf123833ef4be867f5bb18462e356b2ca8dbc0047ee4610915a', 1, 1, 'authToken', '[]', 0, '2022-12-11 19:02:51', '2022-12-11 19:02:51', '2023-12-11 20:02:51'),
('7f37659ec0dc9e6b2717504dc642161de0e99714a90dcfba35fe1f2ad428bfa778a8a75376009ac7', 1, 1, 'authToken', '[]', 0, '2022-12-09 21:22:55', '2022-12-09 21:22:55', '2023-12-09 22:22:55'),
('810cb655410fd5305777888c7dc6c7cfd4788d226b726a55851c2a8345698b7426c85dd9354b81f1', 1, 1, 'authToken', '[]', 0, '2022-12-11 20:03:17', '2022-12-11 20:03:17', '2023-12-11 21:03:17'),
('82dfe8ef0d3b2f6514cc99a2e1ddbe63612ca21be2177ca4760fc7ac46a1c411eb4a4a6b332612df', 1, 1, 'authToken', '[]', 0, '2022-12-11 18:56:25', '2022-12-11 18:56:26', '2023-12-11 19:56:25'),
('84dffac4218d3ce742dfa2fef999ab7a1c0dca47ae71f3d4d65d775da4dd36369982dd479dc2229f', 1, 1, 'authToken', '[]', 1, '2022-12-16 23:17:42', '2022-12-16 23:19:53', '2023-12-17 00:17:42'),
('8a9cca184c2814088b7d71812c6dd761def121ef89178910322efea0b42ba656e17324d0a093de2a', 1, 1, 'authToken', '[]', 0, '2022-12-09 20:35:41', '2022-12-09 20:35:41', '2023-12-09 21:35:41'),
('8ab2f095643c4613f371ad722c72760f767e75439f8c9691087a4a80a2341228210863144f2df605', 1, 1, 'authToken', '[]', 0, '2022-12-26 12:40:12', '2022-12-26 12:40:12', '2023-12-26 13:40:12'),
('8b78b7a816193373a78e74fef3f839841e881295895757e01610a4a291090d664509ac0a9537705b', 1, 1, 'authToken', '[]', 1, '2022-12-22 12:10:55', '2022-12-26 12:38:07', '2023-12-22 13:10:55'),
('8fccd4777cd7c68a0fc0c76ec51d56e2b0f1ad83225d7813287ca8a1df096eaacb7c8425624bafe8', 1, 1, 'authToken', '[]', 0, '2022-12-09 20:17:20', '2022-12-09 20:17:20', '2023-12-09 21:17:20'),
('935891cc7fa519c04e4ff12f5e5f02e6716b5860776a225b002d1f36e35ad644f3f0e696705046e9', 1, 1, 'authToken', '[]', 1, '2022-12-16 23:07:56', '2022-12-16 23:17:27', '2023-12-17 00:07:56'),
('94ce76fc807153306eeb82e3cf68b0ccb2229130199fdc2e4e1188fd6f54aa81df2e1941f3b5d995', 1, 1, 'authToken', '[]', 0, '2023-03-01 20:15:31', '2023-03-01 20:15:31', '2024-03-01 21:15:31'),
('9949490fd6adfecb011dd9a63c3e6bb49ddce4b2f86ee439acdd279d7613326c719a5525a6e884b8', 1, 1, 'authToken', '[]', 1, '2022-12-17 21:09:43', '2022-12-17 21:25:51', '2023-12-17 22:09:43'),
('9abd56390844e02ece34af25b6a5f5b2e108b19d2375d12f498091fa5e8c367fb5aa0ecc59b9456d', 1, 1, 'authToken', '[]', 0, '2022-12-11 20:04:23', '2022-12-11 20:04:23', '2023-12-11 21:04:23'),
('9bfa4150602dce12de06c8d689be3a2328e4496713c7fc66bc186c64a3985c4f581ccd087c8024f0', 1, 1, 'authToken', '[]', 1, '2022-12-16 21:36:01', '2022-12-16 21:37:04', '2023-12-16 22:36:01'),
('a0c67e3b8941eee9b98e25c31c7722508b76c88ae26f8ab0eae248577a24253d4531dd32636d7ac6', 1, 1, 'authToken', '[]', 0, '2022-12-10 12:26:03', '2022-12-10 12:26:03', '2023-12-10 13:26:03'),
('a3ed6caf115f74001a77332f5d861bac33cda76cb5073b4e1377ffcaaf95ce7f28d0ee612a108256', 1, 1, 'authToken', '[]', 0, '2022-12-12 19:29:10', '2022-12-12 19:29:10', '2023-12-12 20:29:10'),
('a5b1ebb50ffcffc63a769e72218b709563fe7b2247b235a9eb5f7134793029fc12e7a7fb2b3f64f5', 1, 1, 'authToken', '[]', 1, '2022-12-17 20:50:14', '2022-12-17 20:53:57', '2023-12-17 21:50:14'),
('a7b556f0b3765041d5632b3671e8ac6850ec7a508e35a569baf2f4eb15c1199cee151594699b7e4c', 1, 1, 'authToken', '[]', 0, '2022-12-11 19:59:00', '2022-12-11 19:59:00', '2023-12-11 20:59:00'),
('a9df27bb8e804b6d5e10c8336ebfa5df12e6550740f019028859faaac2ab9624d4dfcd30f074b194', 1, 1, 'authToken', '[]', 1, '2022-12-11 19:48:30', '2022-12-11 19:48:46', '2023-12-11 20:48:30'),
('ab87045837b017cbea7d3e211975e813b33572f8ade93f654da1e041d957a10c6a8b7f76aeb5738a', 1, 1, 'authToken', '[]', 0, '2022-12-11 21:06:02', '2022-12-11 21:06:02', '2023-12-11 22:06:02'),
('ad4bbdaf716383165484c5e1433cb581f48d345964a2c706ad907d99191a370950849564c4de7c62', 1, 1, 'authToken', '[]', 0, '2022-12-11 18:43:49', '2022-12-11 18:43:49', '2023-12-11 19:43:49'),
('ad6aaddc365634a85482716b15869ecd867b02b2936c80a45d9837211236798c80fe1238ba2c18ec', 1, 1, 'authToken', '[]', 1, '2022-12-11 18:44:45', '2022-12-11 18:45:09', '2023-12-11 19:44:45'),
('ad7ba76a04bad24a9d11aee0f7a3565cd5e799e151fbabca7c4da1718deba093d1515c9a10ecca7a', 1, 1, 'authToken', '[]', 1, '2022-12-18 13:01:54', '2022-12-18 13:08:58', '2023-12-18 14:01:54'),
('b2259540f315003f0c30bfb618c46613e11378d33cf1fc8b3f95cab9427564562a96acf441e8ebdd', 1, 1, 'authToken', '[]', 1, '2022-12-09 21:21:31', '2022-12-09 21:21:35', '2023-12-09 22:21:31'),
('b3c5e0f1541fc732474a00cb61a48c9aded9844b53578aa07df48dc4067dc4a000fc1509194c5856', 1, 1, 'authToken', '[]', 1, '2022-12-17 19:35:43', '2022-12-17 19:44:16', '2023-12-17 20:35:43'),
('b3eb9d9993f907cb8beca95dd5aa0a18fb4bb46ed8c602c62155192b2c24392ebb15a3ab41b83a15', 1, 1, 'authToken', '[]', 1, '2022-12-11 18:48:52', '2022-12-11 18:49:41', '2023-12-11 19:48:52'),
('b41f1e232b07f56fc1f6ad37d79f19bcdc7caf1b2eeb6f93eef233846b6185701d991a8ae713967b', 1, 1, 'authToken', '[]', 1, '2022-12-11 19:51:44', '2022-12-11 19:51:50', '2023-12-11 20:51:44'),
('bc39be687345126c42b057ab53c8e7779bb5bbfb8728fbf9ff1d35403a47212a4226481dd0f6c9e6', 1, 1, 'authToken', '[]', 1, '2022-12-09 21:11:16', '2022-12-09 21:13:28', '2023-12-09 22:11:16'),
('beadaaf3dc1fe3c67f2455455b51e3a5aabad53cc07eeaa9d94382a44e797252e439cbfdeee9b38b', 1, 1, 'authToken', '[]', 1, '2022-12-11 18:42:27', '2022-12-11 18:42:31', '2023-12-11 19:42:27'),
('bf9900f4a2294e094d8ac1bd7bd9b868719796655a73157e3738880e9ebaf71c8f0cbec0c72b93b5', 1, 1, 'authToken', '[]', 0, '2022-12-11 18:41:14', '2022-12-11 18:41:14', '2023-12-11 19:41:14'),
('c1e206e219def018e9c52a6697df8945f5df4d90584132227a28fa67518e335bcf5107307286d978', 1, 1, 'authToken', '[]', 1, '2022-12-11 22:43:36', '2022-12-12 19:28:42', '2023-12-11 23:43:36'),
('c2a9ac44f773279235a681e82f299faba50edd57bab3fcf15f6cc6f980b87b05c921f1ecafd967d0', 1, 1, 'authToken', '[]', 0, '2022-12-10 12:30:02', '2022-12-10 12:30:02', '2023-12-10 13:30:02'),
('c401febe975b242082c0859509a9a1a69887a22a6088b83d0450359d8fd4e52a45c07f2946d291d2', 1, 1, 'authToken', '[]', 1, '2022-12-17 19:44:21', '2022-12-17 19:56:11', '2023-12-17 20:44:21'),
('c594ff11a884bc92f63269bced640efe83633e674276fd9af0ec28518f1ea3ea733594c2bd51735f', 1, 1, 'authToken', '[]', 0, '2022-12-15 20:50:31', '2022-12-15 20:50:32', '2023-12-15 21:50:31'),
('c5f715b650f6b7192247b24ff9c5c7d429b4b11cb0c2e9a139e8399aec4f644f4e6aaa90c693302c', 1, 1, 'authToken', '[]', 0, '2022-12-09 21:13:41', '2022-12-09 21:13:41', '2023-12-09 22:13:41'),
('c85dfc90d89d3253199f830cebc11c01e6d188c006d757b08247c9cbb466bbc8be94ac83ff017d41', 1, 1, 'authToken', '[]', 0, '2022-12-09 20:12:11', '2022-12-09 20:12:11', '2023-12-09 21:12:11'),
('c8d4b408df8513e41b62e45f871c9e270482510d6d8751510c369e7bd66a2d1ad8957af1ce08a77d', 1, 1, 'authToken', '[]', 0, '2022-12-11 22:27:49', '2022-12-11 22:27:49', '2023-12-11 23:27:49'),
('c920dc9a1b9b27434fc971f5672c8b97c8461a31072f45dffefda51f28204d7dac2edfddfedf1c17', 1, 1, 'authToken', '[]', 0, '2022-12-11 18:51:58', '2022-12-11 18:51:58', '2023-12-11 19:51:58'),
('ca89f57f51ecc25e2cecdf1344d94faa9a5a2dc825e2b335f054f0bc0b7db695da52523a05999679', 1, 1, 'authToken', '[]', 1, '2022-12-16 21:42:24', '2022-12-16 22:52:37', '2023-12-16 22:42:24'),
('ceb981ba4bded31accef7bb855a573670d53dc1d8043d253f8eae58a5e2f8f397e079a9b09cd2f1e', 1, 1, 'authToken', '[]', 1, '2022-12-11 19:16:14', '2022-12-11 19:16:28', '2023-12-11 20:16:14'),
('d1ec410a30ec9b223b8308bbc8f6355b6e5ebfe6fdc02a5edf42e997076b3b13be990d2fe90991c4', 1, 1, 'authToken', '[]', 1, '2022-12-09 21:22:45', '2022-12-09 21:22:49', '2023-12-09 22:22:45'),
('dc7ec7ef3be4a4714cee2c536560020548f964b3e92d6c90df3e27f8481e82a133786f6a0e162acc', 1, 1, 'authToken', '[]', 0, '2023-03-03 19:37:33', '2023-03-03 19:37:34', '2024-03-03 20:37:33'),
('dcc42847cb166ec777c2a146d93ef8f81dcc520664c7dac5ad233787a269c4337eb71e12a8de2fef', 1, 1, 'authToken', '[]', 0, '2022-12-09 20:56:54', '2022-12-09 20:56:54', '2023-12-09 21:56:54'),
('deb12f696a48b7d7c2dd8d77106e6f2459b0234cb35f79f3b03760a05752976ce348b8b4a581c79e', 1, 1, 'authToken', '[]', 1, '2022-12-17 21:29:30', '2022-12-17 21:30:07', '2023-12-17 22:29:30'),
('e08160752b8423783770e27e35637a5c3afbc223b239bb82e1aaefea1f545f397ccff4c52987ddb9', 1, 1, 'authToken', '[]', 0, '2022-12-11 18:58:44', '2022-12-11 18:58:44', '2023-12-11 19:58:44'),
('e11051fce72e8c8b140211b67fc556e78d037bf27f1fb2be250281916f919a8a62b04343914978f2', 1, 1, 'authToken', '[]', 1, '2022-12-17 00:09:35', '2022-12-17 00:10:37', '2023-12-17 01:09:35'),
('e1d4d6d61326cde13fbbc4ce20028b69ff4ed53b11ab103966404cbc88c8b1f9a41c03330bd81704', 1, 1, 'authToken', '[]', 1, '2022-12-17 21:04:48', '2022-12-17 21:09:41', '2023-12-17 22:04:48'),
('e44981bc454eeeabe630c39c0220595a7615d4fbb66fc5bf8e2a2914c6a1f73692b710b9570e07b1', 1, 1, 'authToken', '[]', 1, '2022-12-11 19:17:12', '2022-12-11 19:17:22', '2023-12-11 20:17:12'),
('e9489dd0576161bd2644ad716cc8562f7ceca7b1e8788da1918200a81a7bee93e346f42916583865', 1, 1, 'authToken', '[]', 0, '2022-12-09 21:10:14', '2022-12-09 21:10:14', '2023-12-09 22:10:14'),
('ebbceca2aab96685500257dc5e51a4de6615a30e1b2fa7c338ef4a5e57500c318af654095cd5a273', 1, 1, 'authToken', '[]', 1, '2022-12-17 20:04:26', '2022-12-17 20:50:10', '2023-12-17 21:04:26'),
('ee85b523be7a8c19980f49f3ddd7a45fedbc9cf7042587756224f173863c368a2478408a3f1a7ea8', 1, 1, 'authToken', '[]', 0, '2023-03-01 19:11:53', '2023-03-01 19:11:53', '2024-03-01 20:11:53'),
('ef1f125a9ef186c19939bff0cfc8be6897a5702e407aa3fe60e9df39ed6d8367e90e842b117a9613', 1, 1, 'authToken', '[]', 1, '2022-12-16 21:37:15', '2022-12-16 21:38:08', '2023-12-16 22:37:15'),
('f05d5e996fec0877fa6707507fbcc12e6136a9eabb94bab10246df239921ccdfb39d05af83e77622', 1, 1, 'authToken', '[]', 1, '2022-12-19 23:31:07', '2022-12-22 12:10:22', '2023-12-20 00:31:07'),
('f3ddb165e21a15ceb6bd69a888efc2ec830e28958e7a18356af6243108fd2cd9bd6f36849bf3f2f3', 1, 1, 'authToken', '[]', 0, '2022-12-09 21:13:24', '2022-12-09 21:13:24', '2023-12-09 22:13:24'),
('f8e22c6cd1e81bad445c52e2326d963237188601af5ed23ace215c1cdeed723bb4ef603060e223dd', 1, 1, 'authToken', '[]', 0, '2022-12-11 19:18:08', '2022-12-11 19:18:08', '2023-12-11 20:18:08'),
('f9c3820300e78c126b3081994ac31c676ab0653c741388ccae64432c9f5755645e12c00ad3dd442e', 1, 1, 'authToken', '[]', 0, '2022-12-09 20:12:16', '2022-12-09 20:12:16', '2023-12-09 21:12:16'),
('fe1180775d7fd081c3861a1b01099243f0de128bd1f08dc1b76b62a135e81fd66cc1a6d1cd2beb70', 1, 1, 'authToken', '[]', 1, '2022-12-16 23:39:52', '2022-12-16 23:40:42', '2023-12-17 00:39:52');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'yWDdgH5Ners8dbGibMwyZ0YemKwmi54IQqcbjpyV', NULL, 'http://localhost', 1, 0, 0, '2022-12-08 16:46:28', '2022-12-08 16:46:28'),
(2, NULL, 'Laravel Password Grant Client', 'xQRFQ2L1rzt4nUjtBgBMGiMBmrehFFJjR6wf9rSm', 'users', 'http://localhost', 0, 1, 0, '2022-12-08 16:46:28', '2022-12-08 16:46:28');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-12-08 16:46:28', '2022-12-08 16:46:28');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'authToken', '4aa7fa214b287e9128527e4eb1443aafdf8c091d07a32952dbafab03c581cf26', '[\"*\"]', NULL, NULL, '2022-12-08 17:47:27', '2022-12-08 17:47:27'),
(2, 'App\\Models\\User', 1, 'authToken', '0f4305e3e7be26265df39e0e65922f383f38fcd8556ce9472db8d34587ffbb8d', '[\"*\"]', NULL, NULL, '2022-12-08 17:52:36', '2022-12-08 17:52:36'),
(3, 'App\\Models\\User', 1, 'authToken', 'e7c9c9ea9ef8b85fb68ed5f930f905936b054eb714a80b9b0d1180a7c506e640', '[\"*\"]', NULL, NULL, '2022-12-08 17:53:28', '2022-12-08 17:53:28'),
(4, 'App\\Models\\User', 1, 'authToken', 'f56a6d62819fbb3276327090c1e17a9af16c7963caab21774f5ae1a24be7328f', '[\"*\"]', NULL, NULL, '2022-12-08 17:54:35', '2022-12-08 17:54:35'),
(5, 'App\\Models\\User', 1, 'authToken', '6a4be14db5657e78fd0957369aa3bea8aee1bcb2025b12648cbc253f949e39bb', '[\"*\"]', NULL, NULL, '2022-12-08 17:58:04', '2022-12-08 17:58:04'),
(6, 'App\\Models\\User', 1, 'authToken', '436baab07cb8cc0eb3cbc90cb697829a49193c6f4a942bc131a0ac3438d886c2', '[\"*\"]', NULL, NULL, '2022-12-08 18:17:07', '2022-12-08 18:17:07'),
(7, 'App\\Models\\User', 1, 'authToken', '9b2996cebfe2d8e18ad24a8d928557cafc481a2a31240eb8c682984b211805f0', '[\"*\"]', NULL, NULL, '2022-12-09 19:06:25', '2022-12-09 19:06:25');

-- --------------------------------------------------------

--
-- Table structure for table `sa_language`
--

CREATE TABLE `sa_language` (
  `ID` tinyint(3) UNSIGNED NOT NULL,
  `ShortName` char(2) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `Description` char(10) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sa_languages`
--

CREATE TABLE `sa_languages` (
  `LanguageID` varchar(50) NOT NULL DEFAULT '',
  `OrientaionID` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `upload_client`
--

CREATE TABLE `upload_client` (
  `id` int(11) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `nombre_ligne` int(11) DEFAULT NULL,
  `BUID` int(11) DEFAULT NULL,
  `type_upload_client` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_organisation` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `email_verified_at`, `password`, `id_organisation`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'BAHATTE ', 'Youcef', 'youcef.b@ilivik.com', NULL, '$2y$10$7XeLRwa8PcS3DUvF5SlRHOyhK4PgZRr5ktw8xGjF/Z0graBs65n5C', 2, NULL, '2022-12-08 16:58:41', '2022-12-08 16:58:41'),
(3, 'BAHATTE', 'Zakaria', 'bahattezakaria@gmail.com', NULL, 'youcef123456', 2, NULL, '2022-12-24 20:46:42', '2022-12-24 20:46:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ar_routecategoriesgroup`
--
ALTER TABLE `ar_routecategoriesgroup`
  ADD PRIMARY KEY (`GroupID`);

--
-- Indexes for table `ar_territorymapping`
--
ALTER TABLE `ar_territorymapping`
  ADD PRIMARY KEY (`TerritoryID`,`SessionDescription`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hh_area`
--
ALTER TABLE `hh_area`
  ADD PRIMARY KEY (`AreaNo`,`CityNo`,`DistrictNo`,`RegionNo`);

--
-- Indexes for table `hh_ar_customercategories`
--
ALTER TABLE `hh_ar_customercategories`
  ADD PRIMARY KEY (`CategoryId`);

--
-- Indexes for table `hh_ar_customertypes`
--
ALTER TABLE `hh_ar_customertypes`
  ADD PRIMARY KEY (`TypeId`);

--
-- Indexes for table `hh_ar_paymentterms`
--
ALTER TABLE `hh_ar_paymentterms`
  ADD PRIMARY KEY (`TermsBranch`,`TermsId`);

--
-- Indexes for table `hh_ar_salesmencats`
--
ALTER TABLE `hh_ar_salesmencats`
  ADD PRIMARY KEY (`CategoryId`);

--
-- Indexes for table `hh_branch`
--
ALTER TABLE `hh_branch`
  ADD PRIMARY KEY (`BranchNo`);

--
-- Indexes for table `hh_city`
--
ALTER TABLE `hh_city`
  ADD PRIMARY KEY (`CITYNO`,`DistrictNo`,`RegionNo`);

--
-- Indexes for table `hh_customer`
--
ALTER TABLE `hh_customer`
  ADD PRIMARY KEY (`CustomerNo`);

--
-- Indexes for table `hh_customersalestypes`
--
ALTER TABLE `hh_customersalestypes`
  ADD PRIMARY KEY (`SalesTypeID`);

--
-- Indexes for table `hh_district`
--
ALTER TABLE `hh_district`
  ADD PRIMARY KEY (`DistrictNo`,`RegionNo`);

--
-- Indexes for table `hh_ic_warehouse`
--
ALTER TABLE `hh_ic_warehouse`
  ADD PRIMARY KEY (`WarehouseID`);

--
-- Indexes for table `hh_rangesectors`
--
ALTER TABLE `hh_rangesectors`
  ADD PRIMARY KEY (`SectorID`,`RangeID`);

--
-- Indexes for table `hh_region`
--
ALTER TABLE `hh_region`
  ADD PRIMARY KEY (`RegionNo`);

--
-- Indexes for table `hh_route`
--
ALTER TABLE `hh_route`
  ADD PRIMARY KEY (`RouteID`);

--
-- Indexes for table `hh_salesman`
--
ALTER TABLE `hh_salesman`
  ADD PRIMARY KEY (`SalesmanNo`);

--
-- Indexes for table `hh_salesmansector`
--
ALTER TABLE `hh_salesmansector`
  ADD PRIMARY KEY (`SalesmanNo`);

--
-- Indexes for table `hh_salessector`
--
ALTER TABLE `hh_salessector`
  ADD PRIMARY KEY (`SectorID`);

--
-- Indexes for table `hh_sa_bu`
--
ALTER TABLE `hh_sa_bu`
  ADD PRIMARY KEY (`BUID`);

--
-- Indexes for table `hh_sa_bulevels`
--
ALTER TABLE `hh_sa_bulevels`
  ADD PRIMARY KEY (`LevelNo`);

--
-- Indexes for table `hh_staff`
--
ALTER TABLE `hh_staff`
  ADD PRIMARY KEY (`StaffID`);

--
-- Indexes for table `ic_currency`
--
ALTER TABLE `ic_currency`
  ADD PRIMARY KEY (`CurrencyNo`);

--
-- Indexes for table `journeyplan`
--
ALTER TABLE `journeyplan`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `jpcustomer`
--
ALTER TABLE `jpcustomer`
  ADD PRIMARY KEY (`CustomerID`,`JPID`,`IsPotential`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sa_language`
--
ALTER TABLE `sa_language`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `sa_languages`
--
ALTER TABLE `sa_languages`
  ADD PRIMARY KEY (`LanguageID`);

--
-- Indexes for table `upload_client`
--
ALTER TABLE `upload_client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journeyplan`
--
ALTER TABLE `journeyplan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sa_language`
--
ALTER TABLE `sa_language`
  MODIFY `ID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upload_client`
--
ALTER TABLE `upload_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
