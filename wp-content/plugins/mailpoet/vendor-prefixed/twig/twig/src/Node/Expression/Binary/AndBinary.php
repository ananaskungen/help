<?php
namespace MailPoetVendor\Twig\Node\Expression\Binary;
if (!defined('ABSPATH')) exit;
use MailPoetVendor\Twig\Compiler;
class AndBinary extends AbstractBinary
{
 public function operator(Compiler $compiler) : Compiler
 {
 return $compiler->raw('&&');
 }
}
