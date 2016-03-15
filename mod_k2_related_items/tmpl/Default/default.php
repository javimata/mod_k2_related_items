<?php
/**
 * @version		1.0.0
 * @package		Module Items related for K2
 * @author		@Javi_Mata http://www.javimata.com
 * @copyright	Copyright (c) 2016. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;
?>

<div id="k2RelatedItems<?php echo $module->id; ?>" class="k2RelatedItemsBlock<?php if($params->get('moduleclass_sfx')) echo ' '.$params->get('moduleclass_sfx'); ?>">

  <ul class="listRelatedItems">

    <?php foreach ($items as $key=>$item):	?>

    	<?php
    	$classList = ($key%2) ? "odd" : "even"; 
    	if(count($items)==$key+1) $classList .= ' lastItem';
    	$tipo_listado = $item->tipo_listado;
    	$class   = "";
    	$addLink = 1;
    	if ( $tipo_listado == 3 && $item->id == JRequest::getInt('id') ){
    		$class = " active";
    		$addLink = 0;
    	}
    	?>

    	<li class="<?php echo $classList ?> relatedItemList item-<?php echo $item->id; ?><?php echo $class; ?>">

			<?php if($params->get('itemImgShow')!=0): ?>
			<div class="itemImage">
				<a class="linkItemTitle" href="<?php echo $item->link; ?>">
					<img src="<?php echo $item->image; ?>" class="imgItem">
				</a>
			</div>
			<?php endif; ?>


			<?php if($params->get('itemTitle')!=0): ?>
			<div class="itemTitle">
				<?php if ( $params->get('itemTitle')==2 && $addLink==1 ): ?>
				<a class="linkItemTitle" href="<?php echo $item->link; ?>">
					<?php echo $item->title; ?>
				</a>
				<?php else: ?>
				<span class="linkItemTitle">
					<?php echo $item->title; ?>
				</span>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if($params->get('itemImage') || $params->get('itemIntroText')): ?>
			<div class="ItemIntrotext">
				<?php if($params->get('itemImage') && isset($item->image)): ?>
				<a class="moduleItemImage" href="<?php echo $item->link; ?>" title="<?php echo JText::_('K2_CONTINUE_READING'); ?> &quot;<?php echo K2HelperUtilities::cleanHtml($item->title); ?>&quot;">
				<img src="<?php echo $item->image; ?>" alt="<?php echo K2HelperUtilities::cleanHtml($item->title); ?>"/>
				</a>
				<?php endif; ?>

				<?php if($params->get('itemIntroText')): ?>
				<?php echo $item->introtext; ?>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if($params->get('itemReadMore')): ?>
			<a class="ItemReadMore" href="<?php echo $item->link; ?>">
				<?php echo JText::_('K2_READ_MORE'); ?>
			</a>
			<?php endif; ?>

    	</li>
    <?php endforeach; ?>
  
  </ul>

</div>
