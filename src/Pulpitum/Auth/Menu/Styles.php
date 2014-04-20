<?php
namespace Pulpitum\Auth\Menu;
use Request;

class Styles {


	public function renderBootstrap( $structure = array(), $depth = 1 ){
      	$output = self::renderBootstrapItems($structure, $depth);
      	return $output;
	}

	public static function renderBootstrapItems( $structure = array(), $depth = 1){
        $output= '';
        foreach( $structure as $level ){
        	$target = isset($level['target']) ? 'target="'.$level['target'].'"' : "";
            $class = Request::is($level['URL']) ? 'class="active"' : "";
            if( empty( $level['children'] ) ){
                $output .= '<li '.$class.'>';
                $output .= '<a href="'.url($level['URL']).'" '.$target.'>';
                if(isset($level['icon']) and $level['icon']!="")
                    $output .= '<i class="'.$level['icon'].'"></i>';
                $output .= '<span>'.$level['text'].'</span></a>';
            } else{
                if($depth == 1){
                    $output .= '<li class="dropdown">';
                    $output .= '<a class="dropdown-toggle" data-toggle="dropdown">';
                    if(isset($level['icon']) and $level['icon']!="")
                        $output .= '<i class="'.$level['icon'].'"></i>';
                    $output .= '<span>'.$level['text'].'</span></a>';
                }else{
                    $output .= '<li class="dropdown-submenu">';
                    $output .= '<a href="'.url($level['URL']).'" '.$target.'>';
                    if(isset($level['icon']) and $level['icon']!="")
                        $output .= '<i class="'.$level['icon'].'"></i>';
                    $output .= '<span>'.$level['text'].'</span></a>';
                }
                $output .= '<ul  class="dropdown-menu">';
                $output .= self::renderBootstrapItems( $level['children'], ($depth+1) );
                $output .= '</ul>';
            }
            $output .= '</li>';
        }
        return $output;
    }
}

?>