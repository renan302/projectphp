<?php


class GETAddressesV1Request extends Model
{
    public function __construct()
    {
        $this->setProperty("RecordsByPage", [new RequireValidator()]);  
        $this->setProperty("Page", [new RequireValidator()]);  
        $this->setProperty("IdCustomer", [new RequireValidator()]);  
        
        parent::__construct();
    }
}