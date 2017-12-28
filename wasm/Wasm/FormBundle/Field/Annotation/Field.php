<?php
namespace Wasm\FormBundle\Field\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Field
{
    public $type = "text";
    public $label = "";
    public $placeholder = "";
    public $dataSource = null;
    public $dataSourceId = null;
    public $dataSourceProps = null;
    public $dataSourceParams = array();
    public $dataSourceAllOption = false;
    public $virtual = false;
    public $uploadType = "";

    public function getType()
    {
        return $this->type;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function getDataSource()
    {
        return $this->dataSource;
    }

    public function getDataSourceId()
    {
        return $this->dataSourceId;
    }

    public function getDataSourceProps()
    {
        return $this->dataSourceProps;
    }

    public function getDataSourceParams()
    {
        return $this->dataSourceParams;
    }

    public function getDataSourceAllOption()
    {
        return $this->dataSourceAllOption;
    }

    public function isVirtual()
    {
        return $this->virtual;
    }

    public function getUploadType()
    {
        return $this->uploadType;
    }
}