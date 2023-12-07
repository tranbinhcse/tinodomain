<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

// if (!function_exists("resellerclub_GetConfigArray")) {
//     require ROOTDIR . "/modules/registrars/resellerclub/resellerclub.php";
// }
//

require_once 'libs/core.php';

// use WHMCS\Domains\DomainLookup\ResultsList;
// use WHMCS\Domains\DomainLookup\SearchResult;
// use WHMCS\Module\Registrar\Registrarmodule\ApiClient;
use WHMCS\Database\Capsule;

use WHMCS\Domain\TopLevel\ImportItem;
use WHMCS\Results\ResultsList;


    function tinodomain_getConfigArray() {
        $configarray = array(
            "Username" => array(
                "Type" => "text",
                "Size" => "50",
                "Description" => "Enter your username here"
            ),
            "Password" => array(
                "Type" => "password",
                "Size" => "50",
                "Description" => "Enter your password here"
            ),
            "TestMode" => array(
                "Type" => "yesno",
            ),
        );
        return $configarray;
    }

    function tinodomain_RegisterDomain($params = []) {

        try {
            $core = new CoreTNDomainReseller($params);

            $result = $core->Register();


          // if(!$result['success']){
          //     return ["error" => explode('|', $result)];
          // }


            if ($result['error']) {
                return ["error" => $result['error'] ? implode(' | ', $result['error']) : "Wrong response from the server while registering domai1."];
            } else {
                return true;
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_TransferDomain($params = []) {

        try {

            $core = new CoreTNDomainReseller($params);
            $result = $core->Transfer();

            if ($result['order_id'] == '' || !$result['success']) {
                return ["error" => $result['error'] ? implode(' | ', $result['error']) : "Wrong response from the server while transfering domain."];
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_RenewDomain($params = []) {
        try {

            $core = new CoreTNDomainReseller($params);

            $result = $core->Renew();

            if ($result['order_num'] == '' || $result['error']) {
                return ["error" =>  $result['error'] ? implode(' | ', $result['error']) :  "Wrong response from the server while transfering domain."];
            } else {
                return array('success' => 'Domain Renewal Success');
            }
            // return ["error" => "This action is not available for this Domain Registrant"];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_GetEPPCode($params = []) {
        try {

            $core = new CoreTNDomainReseller($params);
            $result = $core->getEppCode();

            if (empty($result) || !$result['success']) {
                return ["error" =>  $result['error'] ? implode(' | ', $result['error']) :  "Wrong response from the server while obtaining domain information."];
            } else {
                return $result['epp_code'];
            }


        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_Sync($params = []) {
        try {
            $core = new CoreTNDomainReseller($params);
            $result = $core->synchInfo();
            if (empty($result) || !$result['success']) {
                return ["error" => $result['error'] ?: "Wrong response from the server while obtaining domain information."];
            } else {
                return [
                    "active" => ($result['status'] == 'Active' ? true : false),
                    "expirydate" => $result["expires"],
                ];
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_GetRegistrarLock($params = []) {
        try {
           ;
            $core = new CoreTNDomainReseller($params);
            $result = $core->getRegistrarLock();
            if (!$result['success']) {
                    return ["error" => $result['error'] ?: "Wrong response from the server while obtaining registrar lock status."];
                } else {
                    return ($result['registrar_lock'] ? 'locked' : 'unlocked');
                }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_SaveRegistrarLock($params = []) {
        try {

            $core = new CoreTNDomainReseller($params);
            $result = $core->updateRegistrarLock();
        if (!$result['success']) {
                return ["error" => $result['error'] ?: "Wrong response from the server while updating registrar lock."];
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_GetContactDetails($params = []) {
        try {

            $core = new CoreTNDomainReseller($params);

            $result = $core->getContactInfo();

        if (empty($result) || !$result['success']) {
                return ["error" =>  $result['error'] ? implode(' | ', $result['error']) :  "Wrong response from the server while obtaining domain contact information."];
            } else {
                return $result['items'];
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_SaveContactDetails($params = []) {
        try {
            $core = new CoreTNDomainReseller($params);
            $result = $core->updateContactInfo();
        if (!$result['success']) {
                return ["error" =>  $result['error'] ? implode(' | ', $result['error']) :  "Wrong response from the server while updating domain contact information."];
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_GetDNS($params = []) {
        try {

            $core = new CoreTNDomainReseller($params);
            $result = $core->getDNSManagement();
        if (empty($result) || !$result['success']) {
                return ["error" => $result['error'] ?: "Wrong response from the server while obtaing DNS records."];
            } else {
                return $result['items'];
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_SaveDNS($params = []) {
        try {

            $core = new CoreTNDomainReseller($params);
            return ["error" => "This action is not available for this Domain Registrant"];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_GetNameservers($params = []) {
        try {
            $core = new CoreTNDomainReseller($params);
            $result = $core->getNameServers();

            if (!$result || $result['error']) {
                return ["error" => $result['error'] ? implode(' | ', $result['error']) : "Wrong response from the server while obtaining domain name servers."];
            } else {
                return $result['items'];
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    function tinodomain_SaveNameservers($params = []) {
        try {
            $core = new CoreTNDomainReseller($params);
            $result = $core->updateNameServers();
            if (empty($result) || !$result['success']) {
                return ["error" => $result['error'] ? implode(' | ', $result['error']) : "Wrong response from the server while obtaining domain name servers."];
            } else {
                return $result['items'];
            }

        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    function tinodomain_GetTldPricing($params = []){


      $core = new CoreTNDomainReseller($params);
      $resp = $core->getTlds();
      $tlds = $resp['tlds'];

      $results = new ResultsList;

      foreach ($tlds as $extension) {
          // All the set methods can be chained and utilised together.
          $extension['minPeriod'] = 1;
          $extension['maxPeriod'] = count($extension['periods']) + 1;
          $extension['registrationPrice'] = $extension['periods'][0]['register'];
          $extension['renewalPrice'] = $extension['periods'][0]['renew'];
          $extension['transferPrice'] = $extension['periods'][0]['transfer'];
          $extension['transferSecretRequired'] = true;

          $prices = $extension['periods'];

          $item = (new ImportItem)
                ->setExtension($extension['tld'])
                ->setMinYears($extension['minPeriod'])
                ->setMaxYears($extension['maxPeriod'])
                ->setRegisterPrice($extension['registrationPrice'])
                ->setRenewPrice($extension['renewalPrice'])
                ->setTransferPrice($extension['transferPrice'])
                ->setRedemptionFeeDays($extension['redemptionDays'])
                ->setRedemptionFeePrice($extension['redemptionFee'])
                ->setCurrency($resp['currency'])
                ->setEppRequired($extension['transferSecretRequired']);
                // ->setYears(
                //   [
                //     1 => ['registrar' => 1, 'renew' => 2, 'transfer' => 3],
                //     2 => ['registrar' => 1, 'renew' => 2, 'transfer' => 3]
                //   ]
                // );

            $results[] = $item;
        }
        return $results;
    }


    function tinodomain_CheckAvailability(array $params){
      $core = new CoreTNDomainReseller($params);
      $result = $core->lookups();
 
      $results = new WHMCS\Domains\DomainLookup\ResultsList();

      foreach ($result as $res) {
          $parts = explode(".", $res['name'], 2);

          $searchResult = new WHMCS\Domains\DomainLookup\SearchResult($parts[0], $parts[1]);
          if ($res["avaliable"] ) {
              $searchResult->setStatus(WHMCS\Domains\DomainLookup\SearchResult::STATUS_NOT_REGISTERED);
          } else {
              if ($res["status"] == "unknown") {

                  $searchResult->setStatus(WHMCS\Domains\DomainLookup\SearchResult::STATUS_TLD_NOT_SUPPORTED);
              } else {

                  $searchResult->setStatus(WHMCS\Domains\DomainLookup\SearchResult::STATUS_REGISTERED);
              }
          }
          // if (array_key_exists("costHash", $domainData)) {
              if ($params["premium"]) {
                  $premiumPricing = array();
          //         if ($type == "Register" && array_key_exists("create", $domainData["costHash"])) {
          //             $premiumPricing["register"] = $domainData["costHash"]["create"];
          //         }
          //         if (array_key_exists("renew", $domainData["costHash"])) {
          //             $premiumPricing["renew"] = $domainData["costHash"]["renew"];
          //         }
          //         if ($type == "Transfer" && array_key_exists("transfer", $domainData["costHash"])) {
          //             $premiumPricing["transfer"] = $domainData["costHash"]["transfer"];
          //         }
          //         if ($premiumPricing) {
          //             $searchResult->setPremiumDomain(true);
          //             $premiumPricing["CurrencyCode"] = $domainData["costHash"]["sellingCurrencySymbol"];
          //             $searchResult->setPremiumCostPricing($premiumPricing);
          //         }
          //     } else {
          //         $searchResult->setStatus(WHMCS\Domains\DomainLookup\SearchResult::STATUS_RESERVED);
          //     }
          }

          $results->append($searchResult);
      }
      return $results;


    }
