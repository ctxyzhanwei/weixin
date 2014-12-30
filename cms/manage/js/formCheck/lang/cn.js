/*
---
script: formcheck.js

description:     A MooTools class that allows you to perform different tests on forms to validate them before submission.

authors:
  - fyrye (http://torntech.com)
  - weepaki
  - floor.ch (http://mootools.floor.ch)
  
copyright: Copyright (c) 2010-2011 

license:
  - MIT License

requires:
  core/1.2.4: '*'
  more/1.2.4.4:
      - Fx.Scroll

provides:
  - FormCheck
...
*/
formcheckLanguage = {
	required: "该项不能为空",
	alpha: "只允许填写字母",
	alphanum: "只允许填写字母和数字",
	nodigit: "No digits are accepted.",
	digit: "请输入整数",
	digitmin: "The number must be at least %0",
	digitltd: "The value must be between %0 and %1",
	number: "Please enter a valid number.",
	email: "请输入正确的邮件地址 <br /><span>比如: yourname@domain.com</span>",
	image : 'This field should only contain image types',
	phone: "Please enter a valid phone.",
	url: "Please enter a valid url: <br /><span>E.g. http://www.domain.com</span>",
	
	confirm: "This field is different from %0",
	differs: "This value must be different of %0",
	length_str: "The length is incorrect, it must be between %0 and %1",
	length_fix: "长度必须为%0个字符",
	lengthmax: "The length is incorrect, it must be at max %0",
	lengthmin: "The length is incorrect, it must be at least %0",
	words_min : "This field must concain at least %0 words, currently: %1 words",
	words_range : "This field must contain %0-%1 words, currently: %2 words",
	words_max : "This field must contain at max %0 words, currently: %1 words",
	checkbox: "Please check the box",
	checkboxes_group : 'Please check at least %0 box(es)',
	radios: "Please select a radio",
	select: "请选择",
	select_multiple : "Please choose at least one value"
}