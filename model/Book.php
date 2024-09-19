<?php
class Product {
    private $id;
    private $name;
    private $description;
    private $language;
    private $publicationDate;
    private $stock;
    private $price;
    private $category_id;
    private $image_url;

    public function __construct($id, $name, $description, $language, $publicationDate, $stock, $price, $category_id, $image_url) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->language = $language;
        $this->publicationDate = $publicationDate;
        $this->stock = $stock;
        $this->price = $price;
        $this->category_id = $category_id;
        $this->image_url = $image_url;
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getLanguage() {
        return $this->language;
    }

    function getPublicationDate() {
        return $this->publicationDate;
    }

    function getStock() {
        return $this->stock;
    }

    function getPrice() {
        return $this->price;
    }
    function getCategoryId() {
        return $this->category_id;
    }

    function getImageUrl() {
        return $this->image_url;
    }
}
?>
