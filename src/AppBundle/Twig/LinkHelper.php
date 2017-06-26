<?php

namespace AppBundle\Twig;

class LinkHelper extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('delete_link', [$this, 'fnDeleteLink'], ['is_safe' => ['all']]),
        ];
    }

    public function fnDeleteLink($target)
    {
        if (is_object($target)) {
            $target = $target->getId();
        }
        return "data-delete-link='$target'";
    }

    public function getName()
    {
        return 'link_helper';
    }
} 