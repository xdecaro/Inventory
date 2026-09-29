<?php
namespace xdecaro\Component\Inventory\Administrator\Table;
defined('_JEXEC') or die; use Joomla\CMS\Table\Table; use Joomla\Database\DatabaseInterface;
final class MovementTable extends Table { public function __construct(DatabaseInterface $db){parent::__construct('#__xdecaroinventory_movements','id',$db);} public function delete($pk=null):bool{throw new \RuntimeException('Inventory movement history is append-only.');} }
