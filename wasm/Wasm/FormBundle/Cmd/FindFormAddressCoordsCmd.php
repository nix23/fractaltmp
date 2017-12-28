<?php
namespace Wasm\FormBundle\Command;

// @todo -> Move in GeoCoder Bundle???
class FindFormAddressCoordsCmd
{
    private $geocoder;
    private $responseFactory;

    public function __construct(
        $geocoder,
        $responseFactory
    ) {
        $this->geocoder = $geocoder;
        $this->responseFactory = $responseFactory;
    }

    public function exec($formData, $sourceAddress = null, $sourceZipCode = null)
    {
        $address = ($sourceAddress) ? $sourceAddress : $formData->entity->getAddress();
        $zipCode = ($sourceZipCode) ? $sourceZipCode : $formData->entity->getZipCode()->getZipCode();

        $coords = $this->geocoder->getCoords($address, $zipCode);

        if($coords != null) {
            $formData->form->addressLat = $coords["lat"];
            $formData->form->addressLon = $coords["lon"];
            return;
        }

        $msg = "Can't get coordinates from your address. ";
        $msg .= "Please enter precise restaurant address and zip code.";

        $error = array();
        $error[$formData->formId] = array("address" => array($msg));
        $this->responseFactory->throwValidationException($error);
    }

    public function execAsApiResponse(
        $formData, 
        $storeData,
        $sourceAddress = null,
        $sourceZipCode = null
    ) {
        $this->handle($formData, $sourceAddress, $sourceZipCode);
        // Can check if response has ID field on frontend
        $storeData->entity = array(
            "addressLat" => $formData->form->addressLat,
            "addressLon" => $formData->form->addressLon,
        );
    }
}