<?php
    class Role {
        
        private $idrole;
        private $name;

        function __construct($id, $roleName) {
            $this->idrole = $id;
            $this->name = $roleName;
        }
    }
?>