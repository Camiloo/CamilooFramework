<?php

class Mage_Navadmin_Helper_Data extends Mage_Core_Helper_Abstract
{

	private $outtree = array();

	public function getSelectcat(){
    	$stores = $this->dataStores();
        if($stores->count() > 0){
        	foreach ($stores as $store){
        		if($store->store_id != 0){
					$this->outtree['value'][] = $store->store_id . '-0';
					$this->outtree['label'][] = 'Root - ' . $store->name;
					$this->drawSelect($store->store_id, 0);
        		}
        	}

        }

        foreach ($this->outtree['value'] as $k => $v){
        	$out[] = array('value'=>$v, 'label'=>$this->outtree['label'][$k]);
        }
		return $out;
	}

	public function drawSelect($store_id=1, $pid=0, $sep=1){
		$spacer = '';
		for ($i = 0; $i <= $sep; $i++){
			$spacer.= '&nbsp;&nbsp;&nbsp;';
		}
		$items = $this->getChildrens($store_id, $pid);
		if(count($items) > 0 ){
			foreach ($items as $item){
				$this->outtree['value'][] = $store_id . '-' . $item['navadmin_id'];
				$this->outtree['label'][] = $spacer . $item['title'];
				$child = $this->getChildrens($store_id, $item['navadmin_id']);
				if(!empty($child)){
					$this->drawSelect($store_id, $item['navadmin_id'], $sep + 1);
				}
			}
		}
		return;
	}

	public function getChildrens($store_id=1, $pid=0){
		$out = array();
        $collection = Mage::getModel('navadmin/navadmin')->getCollection()
        	->addFieldToFilter('pid', array('in'=>$pid) )
        	->addFieldToFilter('store_id', array('in'=>$store_id) )
			->addFieldToFilter('status', array('in'=>'1') )
			->setOrder('position', 'asc');
		foreach ($collection as $item){
			$out[] = $item->getData();
		}
		return $out;
	}

	public function hasChildrens($store_id=1,$pid=0){
        $collection = Mage::getModel('navadmin/navadmin')->getCollection()
        	->addFieldToFilter('pid', array('in'=>$pid) )
        	->addFieldToFilter('store_id', array('in'=>$store_id) )
			->addFieldToFilter('status', array('in'=>'1') )
			->setOrder('position', 'asc')
			->load();
		if($collection->count() > 0){
			return true;
		}
		return false;
	}


    public function drawItem($store_id=1, $pid=0, $level=0)
    {
        $html = '';
        $items = $this->getChildrens($store_id, $pid);
        if (!empty($childrens)) {
            return $html;
        }
		$i = 0;
		$totreg = count($items);
        foreach ($items as $k => $item){
	        $html.= '<li';
	        $hasChildrens = $this->hasChildrens($store_id, $item['navadmin_id']);
	        if ($hasChildrens) {
	             $html.= ' onmouseover="toggleMenu(this,1)" onmouseout="toggleMenu(this,0)"';
	        }

	        $html.= ' class="level'.$level;
	        $html.= ' nav-'.str_replace('/', '-', $item['link']);
	        if ($i == 0){
	        	$html .= ' first';
	        }elseif ($totreg == ($k + 1)){
	        	$html .= ' last';
	        }

	        if ($hasChildrens) {
	            $html .= ' parent';
	        }
	        $html.= '">'."\n";
			
			if($_SERVER['REQUEST_URI'] == $item['link']){
				$classmap = "active";
			}else{
				$classmap = "";				
			}
			
	        if($item['target'] == '_blank'){
	        	$html.= '<a href="'.$item['link'].'"  target="_blank" class="'.$classmap.'">';
	        }else{
	        	$html.= '<a href="'.$item['link'].'" class="'.$classmap.'">';
	        }
	        $html.= '<span>'.$this->htmlEscape($item['title']).'</span></a>'."\n";

	        if ($hasChildrens){
	            $htmlChildren = '';
                $htmlChildren.= $this->drawItem($store_id,$item['navadmin_id'], $level+1);
	            if (!empty($htmlChildren)) {
	                $html.= '<ul class="level' . $level . '">'."\n"
	                        .$htmlChildren
	                        .'</ul>';
	            }
	        }
	        $html.= '</li>'."\n";
	        $i++;
        }
        return $html;
    }

    public function getStores(){
    	$out = array();
    	$stores = $this->dataStores();
        if($stores->count() > 0){
        	$i = 0;
        	foreach ($stores as $store){
        		if($store->store_id != 0){
					$out[$i]['value'] = $store->store_id;
					$out[$i]['label'] = $store->name;
					$i++;
        		}
        	}
        }
        return $out;
    }

    public function dataStores(){
    	$stores = Mage::getModel('core/store')
                ->getResourceCollection()
                ->addFieldToFilter('is_active', array('in'=>'1') )
                ->setLoadDefault(true)
                ->load();
         return $stores;
    }
}