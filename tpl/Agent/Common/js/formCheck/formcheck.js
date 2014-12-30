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
/*
	Class: FormCheck
		Performs different tests on forms and indicates errors.

	Usage:
		Works with these types of fields :
		- input (text, radio, checkbox)
		- textarea
		- select

		You just need to add a specific class to each fields you want to check.
		For example, if you add the class
			(code)
			validate['required','length[4, -1]','differs:email','digit']
			(end code)
		the value's field must be set (required) with a minimum length of four chars (4, -1),
		must differs of the input named email (differs:email), and must be digit.

		You can perform check during the datas entry or on the submit action, shows errors as tips or in a div before or after the field,
		show errors one by one or all together, show a list of all errors at the top of the form, localize error messages, add new regex check, ...

		The layout is design only with css. Now I added a hack to use transparent png with IE6, so you can use png images in formcheck.css (works only for theme, so the file must be named formcheck.css). It can also works with multiple forms on a single html page.
		The class supports now internationalization. To use it, simply specify a new <script> element in your html head, like this : <script type="text/javascript" src="formcheck/lang/fr.js"></script>.

		If you add the class
			(code)
			validate['submit']
			(end code)
		to an element like an anchor (or anything else), this element will act as a submit button.

		N.B. : you must load the language script before the formcheck and this method overpass the old way. You can create new languages following existing ones. You can otherwise still specifiy the alerts' strings when you initialize the Class, with options.
		If you don't use a language script, the alert will be displayed in english.

	Test type:
		You can perform various test on fields by adding them to the validate class. Be careful to *not use space chars*. Here is the list of them.

		required 					- The field becomes required. This is a regex, you can change it with class options.
		alpha 						- The value is restricted to alphabetic chars. This is a regex, you can change it with class options.
		alphanum 					- The value is restricted to alphanumeric characters only. This is a regex, you can change it with class options.
		nodigit 					- The field doesn't accept digit chars. This is a regex, you can change it with class options.
		digit 						- The value is restricted to digit (no floating point number) chars, you can pass two arguments (f.e. digit[21,65]) to limit the number between them. Use -1 as second argument to set no maximum.
		number 						- The value is restricted to number, including floating point number. This is a regex, you can change it with class options.
		email 						- The value is restricted to valid email. This is a regex, you can change it with class options.
		image						- The value is restricted to images (jpg, jpeg, png, gif, bmp). This is a regex, you can change it with class options.
		phone 						- The value is restricted to phone chars. This is a regex, you can change it with class options.
		phone_inter					- The value is restricted to international phone number. This is a regex, you can change it with class options.
		url: 						- The value is restricted to url. This is a regex, you can change it with class options.
		confirm 					- The value has to be the same as the specified. f.e. confirm:password.
		differs 					- The value has to be diferent as the one specifies. f.e. differs:user.
		length 						- The value length is restricted by argument (f.e. length[6,10]). Use -1 as second argument to set no maximum.
		group						- Use to validate several checkboxes as a group. Requires 2 arguments, the second one being optional (1 by default): the group id and the minimum amount of boxes to check. The second argument may be set on any or all items of the group. See example below.
		words						- The words number is limited by arguments. f.e. words[1,13]. Use -1 as second argument to don't have a max limit.
		target						- It's not really a validation test, but it allows you to attach the error message to an other element, usefull if the input you validate is hidden. You must specifiy target id, f.e. target:myDiv.

		You can also use a custom function to check a field. For example, if you have a field with class
			(code)
			validate['required','%customCheck']
			(end code)
		the function customCheck(el) will be called to validate the field. '%customcheck' works with other validate(s) together, and '~customcheck' works if the element pass the other validate(s).
		Here is an example of what customCheck could look :
			(code)
			function customCheck(el){
				if (!el.value.test(/^[A-Z]/)) {
					el.errors.push("Username should begin with an uppercase letter");
					return false;
				} else {
					return true;
				}
			}
			(end code)

		To validate checkoxes group, you could make something like :
			(code)
				<input type="checkbox" name="dog" class="validate['group[1,2]']">
				<input type="checkbox" name="cat" class="validate['group[1]']">
				<input type="checkbox" name="mouse" class="validate['group[1]']">
			(end code)
		For checkboxes from group 1, you will need to check at least 2 boxes.

		It is now possible to register new fields after a new FormCheck call by using <FormCheck::register> (see <FormCheck::dispose> too). You need first to add the validate class to the element you want to register ( $('myInput').addClass("validate['required']") ).

	Parameters:
		When you initialize the class with addEvent, you can set some options. If you want to modify regex, you must do it in a hash, like for display or alert. You can also add new regex check method by adding the regex and an alert with the same name.

		Required:

			form_id - The id of the formular. This is required.

		Optional:

			submit					- If you turn this option to false, the FormCheck will only perform a validation, without submitting the form, even on success. You can use validateSuccess event to execute some code.

			submitByAjax 			- you can set this to true if you want to submit your form with ajax. You should use provided events to handle the ajax request (see below). By default it is false.
			ajaxResponseDiv 		- id of element to inject ajax response into (can also use onAjaxSuccess). By default it is false.
			ajaxEvalScripts 		- use evalScripts in the Request response. Can be true or false, by default it is false.
			onAjaxRequest 			- Function to fire when the Request event starts.
			onAjaxComplete 			- Function to fire when the Request event completes regardless of and prior to Success or Failure.
			onAjaxSuccess 			- Function to fire when the Request receives .  Args: response [the request response] - see Mootools docs for Request.onSuccess.
			onAjaxFailure 			- Function to fire if the Request fails.

			onSubmit				- Function to fire when form is submited (so before validation)
			onValidateSuccess 		- Function to fire when validation pass (you should prevent form submission with option submit:false to use this)
			onValidateFailure		- Function to fire when validation fails

			tipsClass 				- The class to apply to tipboxes' errors. By default it is 'fc-tbx'.
			errorClass 				- The class to apply to alertbox (not tips). By default it is 'fc-error'.
			fieldErrorClass 		- The class to apply to fields with errors, except for radios. You should also turn on  options.addClassErrorToField. By default it is 'fc-field-error'

			trimValue				- If set to true, strip whitespace (or other characters) from the beginning and end of values. By default it is false.
			validateDisabled		- If set to true, disabled input will be validated too, otherwise not.

		Display:
			This is a hash of display settings. in here you can modify.

			showErrors 				- 0 : onSubmit, 1 : onSubmit & onBlur, by default it is 0.
			titlesInsteadNames		- 0 : When you do a check using differs or confirm, it takes the field name for the alert. If it's set to 1, it will use the title instead of the name.
			errorsLocation 			- 1 : tips, 2 : before, 3 : after, by default it is 1.
			indicateErrors 			- 0 : none, 1 : one by one, 2 : all, by default it is 1.
			indicateErrorsInit		- 0 : determine if the form must be checked on initialize. Could be usefull to force the user to update fields that don't validate.
			keepFocusOnError 		- 0 : normal behaviour, 1 : the current field keep the focus as it remain errors. By default it is 0.
			checkValueIfEmpty 		- 0 : When you leave a field and you have set the showErrors option to 1, the value is tested only if a value has been set. 1 : The value is tested  in any case.  By default it is 1.
			addClassErrorToField 	- 0 : no class is added to the field, 1 : the options.fieldErrorClass is added to the field with an error (except for radio). By default it is 0.
			removeClassErrorOnTipClosure - 0 : Error class is kept when the tip is closed, 1 : Error class is removed when the tip is closed

			fixPngForIe 			- 0 : do nothing, 1 : fix png alpha for IE6 in formcheck.css. By default it is 1.
			replaceTipsEffect 		- 0 : No effect on tips replace when we resize the broswer, 1: tween transition on browser resize;
			closeTipsButton 		- 0 : the close button of the tipbox is hidden, 1 : the close button of the tipbox is visible. By default it is 1.
			flashTips 				- 0 : normal behaviour, 1 : the tipbox "flash" (disappear and reappear) if errors remain when the form is submitted. By default it is 0.
			tipsPosition 			- 'right' : the tips box is placed on the right part of the field, 'left' to place it on the left part. By default it is 'right'.
			tipsOffsetX 			- Horizontal position of the tips box (margin-left), , by default it is 100 (px).
			tipsOffsetY				- Vertical position of the tips box (margin-bottom), , by default it is -10 (px).

			listErrorsAtTop 		- List all errors at the top of the form, , by default it is false.
			scrollToFirst 			- Smooth scroll the page to first error and focus on it, by default it is true.
			fadeDuration 			- Transition duration (in ms), by default it is 300.

		Alerts:
			This is a hash of alerts settings. in here you can modify strings to localize or wathever else. %0 and %1 represent the argument.

			required 				- "This field is required."
			alpha 					- "This field accepts alphabetic characters only."
			alphanum 				- "This field accepts alphanumeric characters only."
			nodigit 				- "No digits are accepted."
			digit 					- "Please enter a valid integer."
			digitmin 				- "The number must be at least %0"
			digitltd 				- "The value must be between %0 and %1"
			number 					- "Please enter a valid number."
			email 					- "Please enter a valid email: <br /><span>E.g. yourname@domain.com</span>"
			phone 					- "Please enter a valid phone."
			phone_inter 			- "Please enter a valid international phone number."
			url 					- "Please enter a valid url: <br /><span>E.g. http://www.domain.com</span>"
			image					- "This field should only contain image types"
			confirm 				- "This field is different from %0"
			differs 				- "This value must be different of %0"
			length_str 				- "The length is incorrect, it must be between %0 and %1"
			length_fix 				- "The length is incorrect, it must be exactly %0 characters"
			lengthmax 				- "The length is incorrect, it must be at max %0"
			lengthmin 				- "The length is incorrect, it must be at least %0"
			words_min				- "This field must concain at least %0 words, now it has %1 words"
			words_range				- "This field must contain between %0 and %1 words, now it has %2 words"
			words_max				- "This field must contain at max %0 words, now it has %1 words"
			checkbox 				- "Please check the box"
			checkboxes_group		- "Please check at least %0 box(es)"
			radios 					- "Please select a radio"
			select 					- "Please choose a value"

	Example:
		You can initialize a formcheck (no scroll, custom classes and alert) by adding for example this in your html head this code :

		(code)
		<script type="text/javascript">
			window.addEvent('domready', function() {
				var myCheck = new FormCheck('form_id', {
					tipsClass : 'tips_box',
					display : {
						scrollToFirst : false
					},
					alerts : {
						required : 'This field is ablolutely required! Please enter a value'
					}
				})
			});
		</script>
		(end code)

	About:
		formcheck.js v.1.6 for mootools v1.2 - 03 / 2010

		by Mootools.Floor (http://mootools.floor.ch) MIT-style license

		Created by Luca Pillonel (luca-at-nolocation.org),
		Last modified by Luca Pillonel

	Credits:
		This class was inspired by fValidator by Fabio Zendhi Nagao (http://zend.lojcomm.com.br)

		Thanks to all contributors from groups.google.com/group/moofloor (and others as well!) providing ideas, translations, fixes and motivation!
*/

var FormCheck = new Class({

	Implements: [Options, Events],

	options : {

		tipsClass : 'fc-tbx',				//tips error class
		errorClass : 'fc-error',			//div error class
		fieldErrorClass : 'fc-field-error',	//error class for elements

		submit : true,						//false : just validate the form and do nothing else. Use onValidateSuccess event to execute some code
		submitAction: false,				//Action page used to submit the form data to.
		submitMethod: false,				//Method used to submit the form, valid options : 'post' or 'get'

		trimValue : false,					//trim (remove whitespaces before and after) the value
		validateDisabled : false,			//skip validation on disabled input if set to false.

		submitByAjax : false,				//false : standard submit way, true : submit by ajax
		ajaxResponseDiv : false,			//element to inject ajax response into (can also use onAjaxSuccess) [cronix]
		ajaxEvalScripts : false,			//use evalScripts in the Request response [cronix]
		onAjaxRequest : $empty,				//Function to fire when the Request event starts
		onAjaxComplete : $empty,			//Function to fire when the Request is complete, before and regardless of Success or Failure
		onAjaxSuccess : $empty,				//Function to fire when the Request receives .  Args: response [the request response] - see Mootools docs for Request.onSuccess
		onAjaxFailure : $empty,				//Function to fire if the Request fails

		onSubmit		  : $empty,			//Function to fire when user submit the form
		onValidateSuccess : $empty,			//Function to fire when validation pass
		onValidateFailure : $empty,			//Function to fire when validation fails

		display : {
			showErrors : 0,
			titlesInsteadNames : 0,
			errorsLocation : 1,
			indicateErrors : 1,
			indicateErrorsInit : 0,
			keepFocusOnError : 0,
			checkValueIfEmpty : 1,
			addClassErrorToField : 0,
			removeClassErrorOnTipClosure : 0,
			fixPngForIe : 1,
			replaceTipsEffect : 1,
			flashTips : 0,
			closeTipsButton : 1,
			tipsPosition : "right",
			tipsOffsetX : -45,
			tipsOffsetY : 0,
			listErrorsAtTop : false,
			scrollToFirst : true,
			fadeDuration : 300
		},

		alerts : {
			required : "This field is required.",
			alpha : "This field accepts alphabetic characters only.",
			alphanum : "This field accepts alphanumeric characters only.",
			nodigit : "No digits are accepted.",
			digit : "Please enter a valid integer.",
			digitltd : "The value must be between %0 and %1",
			number : "Please enter a valid number.",
			email : "Please enter a valid email.",
			image : 'This field should only contain image types',
			phone : "Please enter a valid phone.",
			phone_inter : "Please enter a valid international phone number.",
			url : "Please enter a valid url.",

			confirm : "This field is different from %0",
			differs : "This value must be different of %0",
			length_str : "The length is incorrect, it must be between %0 and %1",
			length_fix : "The length is incorrect, it must be exactly %0 characters",
			lengthmax : "The length is incorrect, it must be at max %0",
			lengthmin : "The length is incorrect, it must be at least %0",
			words_min : "This field must concain at least %0 words, currently: %1 words",
			words_range : "This field must contain %0-%1 words, currently: %2 words",
			words_max : "This field must contain at max %0 words, currently: %1 words",
			checkbox : "Please check the box",
			checkboxes_group : 'Please check at least %0 box(es)',
			radios : "Please select a radio",
			select : "Please choose a value",
			select_multiple : "Please choose at least one value"
		},

		regexp : {
			required : /[^.*]/,
			alpha : /^[a-z ._-]+$/i,
			alphanum : /^[a-z0-9 ._-]+$/i,
			digit : /^[-+]?[0-9]+$/,
			nodigit : /^[^0-9]+$/,
			number : /^[-+]?\d*\.?\d+$/,
			email : /^([a-zA-Z0-9_\.\-\+%])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
			image : /.(jpg|jpeg|png|gif|bmp)$/i,
			phone : /^\+{0,1}[0-9 \(\)\.\-]+$/, // alternate regex : /^[\d\s ().-]+$/,/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/
			phone_inter : /^\+{0,1}[0-9 \(\)\.\-]+$/,
			url : /^(http|https|ftp)\:\/\/[a-z0-9\-\.]+\.[a-z]{2,3}(:[a-z0-9]*)?\/?([a-z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*$/i
		}
	},

	/*
	Constructor: initialize
		Constructor

		Add event on formular and perform some stuff, you now, like settings, ...
	*/
	initialize : function(form, options) {
		if (this.form = $(form)) {
			this.form.isValid = true;
			this.regex = ['length'];
			this.groups = {};

			//internalization
			if (typeof(formcheckLanguage) != 'undefined') this.options.alerts = $merge(this.options.alerts, formcheckLanguage);

			this.setOptions(options);

			this.form.setProperty('action',
				this.options.submitAction || this.form.getProperty('action') || 'post');

			this.form.setProperty('method',
				this.options.submitMethod || this.form.getProperty('method') || '');

			this.validations = [];
			this.alreadyIndicated = false;
			this.firstError = false;

			$H(this.options.regexp).each(function(el, key) {
				this.regex.push(key);
			}, this);

			this.form.getElements("*[class*=validate]").each(function(el) {
				this.register(el);
			}, this);

			this.form.addEvents({
				"submit": this.onSubmit.bind(this)
			});

			if(this.options.display.fixPngForIe) this.fixIeStuffs();
			document.addEvent('mousewheel', function(){
				this.isScrolling = false;
			}.bind(this));

			if (this.options.display.indicateErrorsInit) {
				this.validations.each(function(el) {
					if(!this.manageError(el,'submit')) this.form.isValid = false;
				}, this);
			}
		}
	},

	/*
	Function: register
		Allows you to declare afterward new fields to the formcheck, to check dynamically loaded fields for example.
		By default it will be the last element to be validated as it's added after others inputs, but you can define a position with second parameter.

	Example:
		(code)
		<script type="text/javascript">
			window.addEvent('domready', function() {
				formcheck = new FormCheck('form_id');
			});

			// ...some code...

			var newField = new Element('input', {
				class	: "validate['required']",
				name	: "new-field"
			}).inject('form_id');
			formcheck.register(newField, 3);

			new Element('input', {
				class	: "validate['required']",
				name	: "another-field",
				id		: "another-field"
			}).inject('form_id');
			formcheck.register($('another-field'));
		</script>
		(end code)

	See also:
		<FormCheck::dispose>
	*/
	register : function(el, position) {
		el.validation = [];
		el.getProperty("class").split(' ').each(function(classX) {
			if (classX.match(/^validate(\[.+\])$/)) {
				var valid = true;

				var validators = eval(classX.match(/^validate(\[.+\])$/)[1]);
				for(var i = 0; i < validators.length; i++) {
					el.validation.push(validators[i]);
					if (validators[i].match(/^confirm:/)) {
						var field = validators[i].match(/.+:(.+)$/)[1];
						if (this.form[field].validation.contains('required')) el.validation.push('required');
					}
					if (validators[i].match(/^target:.+/)) {
						el.target = validators[i].match(/^target:(.+)/)[1];
					}
				}

				//we check if group is already registered
				el.isChild = this.isChildType(el, validators);
				if (el.isChild && el.type == 'radio') {
					this.validations.each(function(registeredEl){
						if (registeredEl.name == el.name) valid = false;
					}, this);
				}
				if (el.isChild && el.type == 'checkbox') {
					this.validations.each(function(registeredEl){
						if (registeredEl.groupID == el.groupID) valid = false;
					}, this);
				}

				if (position && position <= this.validations.length) {
					var newValidations = [];
					this.validations.each(function(valider, i){
						if (position == i+1 && valid) {
							newValidations.push(el);
							this.addListener(el);
						}
						newValidations.push(valider);
					}, this);
					this.validations = newValidations;
				} else if (valid) {
					this.validations.push(el);
					this.addListener(el);
				}
			}
		}, this);
	},

	/*
	Function: dispose
		Allows you to remove a declared field from formCheck

	Example:
		(code)
		<script type="text/javascript">
			window.addEvent('domready', function() {
				formcheck = new FormCheck('form_id');
			});

			// ...some code...

			formcheck.dispose($('obsolete-field'));
		</script>
		(end code)

	See also:
		<FormCheck::register>
	*/
	dispose : function(element) {
		this.validations.erase(element);
	},

	/*
	Function: addListener
		Private method

		Add listener on fields
	*/
	addListener : function(el) {
		el.errors = [];

		if (el.validation[0] == 'submit') {
			el.addEvent('click', function(e){
				new Event(e).stop();
				if (this.onSubmit(e)) this.form.submit();
			}.bind(this));
			return true;
		}

		if (!el.isChild) {
			el.addEvent('blur', function() {
				if(!this.fxRunning && (el.element || this.options.display.showErrors == 1) && (this.options.display.checkValueIfEmpty || el.value)) this.manageError(el, 'blur');
			}.bind(this));
		//We manage errors on radio
		} else if(el.isChild && el.type == 'radio') {
			//We get all radio from the same group and add a blur option
			var radioGroup = this.form.getElements('input[name="'+ el.getProperty("name") +'"]');
			radioGroup.each(function(radio){
				radio.addEvent('blur', function(){
					if(!this.fxRunning && (el.element || this.options.display.showErrors == 1) && (this.options.display.checkValueIfEmpty || el.value)) this.manageError(el, 'click');
				}.bind(this));
			},this);
		}
	},

	/*
	Function: manageError
		Private method

		Manage display of errors boxes
	*/
	manageError : function(el, method) {
		var isValid = this.validate(el);
		if (method == 'testonly') return isValid;
		if ((!isValid && el.validation.contains('required')) || (el.value && !isValid)) {
			if(this.options.display.listErrorsAtTop && method == 'submit') this.listErrorsAtTop(el);
			if (this.options.display.indicateErrors == 2 ||this.alreadyIndicated == false || el == this.alreadyIndicated) {
				if(!this.firstError) this.firstError = el;
				this.alreadyIndicated = el;

				if (this.options.display.keepFocusOnError && el == this.firstError) {
					(function(){el.focus()}).delay(10);
				}
				this.addError(el);
				return false;
			}
		} else if ((isValid || (!el.validation.contains('required') && !el.value))) {
			this.removeError(el);
			return true;
		}
		return true;
	},

	/*
	Function: validate
		Private method

		Dispatch check to other methods
	*/
	validate : function(el) {
		el.errors = [];
		el.isOk = true;

		//skip validation for disabled fields and trim if specified
		if (!this.options.validateDisabled && el.get('disabled')) return true;
		if (this.options.trimValue && el.value) el.value = el.value.trim();

		el.validation.each(function(rule) {
			if(el.isChild) {
				if (!this.validateGroup(el)) el.isOk = false;
			} else {
				var ruleArgs = [];

				if(rule.match(/target:.+/)) return;
				var ruleMethod = rule;
				if(rule.match(/^.+\[/)) {
					ruleMethod = rule.split('[')[0];
					ruleArgs = eval(rule.match(/^.+(\[.+\])$/)[1].replace(/([A-Z0-9\._-]+)/i, "'$1'"));
				}

				if (this.regex.contains(ruleMethod) && el.get('tag') != "select") {
					if (this.validateRegex(el, ruleMethod, ruleArgs) == false) {
						el.isOk = false;
					}
				}
				if (rule.match(/confirm:.+/)) {
					ruleArgs = [rule.match(/.+:(.+)$/)[1]];
					if (this.validateConfirm(el, ruleArgs) == false) {
						el.isOk = false;
					}
				}
				if (rule.match(/differs:.+/)) {
					ruleArgs = [rule.match(/.+:(.+)$/)[1]];
					if (this.validateDiffers(el, ruleArgs) == false) {
						el.isOk = false;
					}
				}
				if (ruleMethod == 'words') {
					if (this.validateWords(el, ruleArgs) == false) {
						el.isOk = false;
					}
				}
				if (ruleMethod == 'required' && (el.get('tag') == "select" || el.type == "checkbox")) {
					if (this.simpleValidate(el) == false) {
						el.isOk = false;
					}
				}
				if(rule.match(/%[A-Z0-9\._-]+$/i) || (el.isOk && rule.match(/~[A-Z0-9\._-]+$/i))) {
					if(eval(rule.slice(1)+'(el)') == false) {
						el.isOk = false;
					}
				}
			}
		}, this);
		return ( el.isOk ) ? true : false;
	},

	/*
	Function: simpleValidate
		Private method

		Perform simple check for select fields and checkboxes
	*/
	simpleValidate : function(el) {
		if(el.get('tag') == 'select'){
			if(!el.multiple) {
				if(el.selectedIndex <= 0) {
					el.errors.push(this.options.alerts.select);
					return false;
				}
			} else {
				var selected = false;
				el.getChildren('option').each(function(el){
					if(el.selected) selected = true;
				});

				if(!selected){
					el.errors.push(this.options.alerts.select_multiple);
					return false;
				}
			}
		} else if (el.type == "checkbox" && el.checked == false) {
			el.errors.push(this.options.alerts.checkbox);
			return false;
		}
		return true;
	},

	/*
	Function: validateRegex
		Private method

		Perform regex validations
	*/
	validateRegex : function(el, ruleMethod, ruleArgs) {
		var msg = "";
		if (ruleMethod == 'length' && ruleArgs[1]) {
			if (ruleArgs[1] == -1) {
				this.options.regexp.length = new RegExp("^[\\s\\S]{"+ ruleArgs[0] +",}$");
				msg = this.options.alerts.lengthmin.replace("%0",ruleArgs[0]);
			} else if(ruleArgs[0] == ruleArgs[1]) {
				this.options.regexp.length = new RegExp("^[\\s\\S]{"+ ruleArgs[0] +"}$");
				msg = this.options.alerts.length_fix.replace("%0",ruleArgs[0]);
			} else {
				this.options.regexp.length = new RegExp("^[\\s\\S]{"+ ruleArgs[0] +","+ ruleArgs[1] +"}$");
				msg = this.options.alerts.length_str.replace("%0",ruleArgs[0]).replace("%1",ruleArgs[1]);
			}
		} else if (ruleArgs[0] && ruleMethod == 'length') {
			this.options.regexp.length = new RegExp("^.{0,"+ ruleArgs[0] +"}$");
			msg = this.options.alerts.lengthmax.replace("%0",ruleArgs[0]);
		} else {
			msg = this.options.alerts[ruleMethod];
		}
		if ((ruleMethod == 'digit' || ruleMethod == 'number') && ruleArgs[1]) {
			var valueres, regres = true;
			if (!this.options.regexp[ruleMethod].test(el.value)) {
				el.errors.push(this.options.alerts[ruleMethod]);
				regres = false;
			}
			if (ruleArgs[1] == -1) {
				valueres = ( el.value.toFloat() >= ruleArgs[0].toFloat() );
				msg = this.options.alerts.digitmin.replace("%0",ruleArgs[0]);
			} else {
				valueres = ( el.value.toFloat() >= ruleArgs[0].toFloat() && el.value.toFloat() <= ruleArgs[1].toFloat() );
				msg = this.options.alerts.digitltd.replace("%0",ruleArgs[0]).replace("%1",ruleArgs[1]);
			}
			if (regres == false || valueres == false) {
				el.errors.push(msg);
				return false;
			}
		} else if (this.options.regexp[ruleMethod].test(el.value) == false)  {
			el.errors.push(msg);
			return false;
		}
		return true;
	},

	/*
	Function: validateConfirm
		Private method

		Perform confirm validations
	*/
	validateConfirm: function(el,ruleArgs) {
		var confirm = ruleArgs[0];
		if(el.value != this.form[confirm].value){
			var msg = ( this.options.display.titlesInsteadNames ) ?
				this.options.alerts.confirm.replace("%0",this.form[confirm].getProperty('title')) :
				this.options.alerts.confirm.replace("%0",confirm);
			el.errors.push(msg);
			return false;
		}
		return true;
	},

	/*
	Function: validateDiffers
		Private method

		Perform differs validations
	*/
	validateDiffers: function(el,ruleArgs) {
		var differs = ruleArgs[0];
		if(el.value == this.form[differs].value){
			var msg = ( this.options.display.titlesInsteadNames ) ?
				this.options.alerts.differs.replace("%0",this.form[differs].getProperty('title')) :
				this.options.alerts.differs.replace("%0",differs);
			el.errors.push(msg);
			return false;
		}
		return true;
	},

	/*
	Function: validateWords
		Private method

		Perform word count validation
	*/
	validateWords: function(el,ruleArgs) {
		var min = ruleArgs[0];
		var max = ruleArgs[1];

		var words = el.value.replace(/[ \t\v\n\r\f\p]/m, ' ').replace(/[,.;:]/g, ' ').clean().split(' ');

		if(max == -1) {
			if(words.length < min) {
				el.errors.push(this.options.alerts.words_min.replace("%0", min).replace("%1", words.length));
				return false;
			}
		} else {
			if(min > 0)	{
				if(words.length < min || words.length > max) {
					el.errors.push(this.options.alerts.words_range.replace("%0", min).replace("%1", max).replace("%2", words.length));
					return false;
				}
			} else {
				if(words.length > max) {
					el.errors.push(this.options.alerts.words_max.replace("%0", max).replace("%1", words.length));
					return false;
				}
			}
		}
		return true;
	},


	/*
	Function: isFormValid
		public method

		Determine if the form is valid

		Return true or false
	*/
    isFormValid: function() {
		this.form.isValid = true;
		this.validations.each(function(el) {
			var validation = this.manageError(el,'testonly');
			if(!validation) this.form.isValid = false;
		}, this);
		return this.form.isValid;
	},

	/*
	Function: isChildType
		Private method

		Determine if the field is a group of radio, of checkboxes or not.
	*/
	isChildType: function(el, validators) {
		var validator;
		if($defined(el.type) && el.type == 'radio') {
			return true;
		} else if(validator = validators.join().match(/group(\[.*\])/)) {
			var group = eval(validator[1]);
			this.groups[group[0]] = this.groups[group[0]] || [];
			this.groups[group[0]][0] = this.groups[group[0]][0] || [];
			this.groups[group[0]][1] = group[1] || this.groups[group[0]][1] || 1;
			this.groups[group[0]][0].push(el);
			el.groupID = group[0];
			return true;
		}
		return false;
	},

	/*
	Function: validateGroup
		Private method

		Perform radios validations
	*/
	validateGroup : function(el) {
		el.errors = [];
		if(el.type == 'radio') {
			var nlButtonGroup = this.form[el.getProperty("name")];
			el.group = nlButtonGroup;
			var cbCheckeds = false;

			for(var i = 0; i < nlButtonGroup.length; i++) {
				if(nlButtonGroup[i].checked) {
					cbCheckeds = true;
				}
			}
			if(cbCheckeds == false) {
				el.errors.push(this.options.alerts.radios);
				return false;
			} else {
				return true;
			}
		// we have group of checkboxes
		} else if(el.type == 'checkbox') {
			//we get length of checked elements
			var checked = 0;
			this.groups[el.groupID][0].each(function(groupEl){
				if(groupEl.checked) checked++;
			});
			if(checked >= this.groups[el.groupID][1]) {
				return true;
			} else {
				( this.groups[el.groupID][0].length > 1 ) ?
					el.errors.push(this.options.alerts.checkboxes_group.replace('%0', this.groups[el.groupID][1])) :
					el.errors.push(this.options.alerts.checkbox);
				return false;
			}
		// we have unmanaged type
		} else {
			return false;
		}
	},

	/*
	Function: listErrorsAtTop
		Private method

		Display errors
	*/
	listErrorsAtTop : function(obj) {
		if(!this.form.element) {
			 this.form.element = new Element('div', {'id' : 'errorlist', 'class' : this.options.errorClass}).injectTop(this.form);
		}
		if ($type(obj) == 'collection') {
			new Element('p').set('html',"<span>" + obj[0].name + " : </span>" + obj[0].errors[0]).injectInside(this.form.element);
		} else {
			if ((obj.validation.contains('required') && obj.errors.length > 0) || (obj.errors.length > 0 && obj.value && obj.validation.contains('required') == false)) {
				obj.errors.each(function(error) {
					new Element('p').set('html',"<span>" + obj.name + " : </span>" + error).injectInside(this.form.element);
				}, this);
			}
		}
		window.fireEvent('resize');
	},

	/*
	Function: addError
		Private method

		Add error message
	*/
	addError : function(obj) {
		//determine position
		var coord = obj.target ? $(obj.target).getCoordinates() : obj.getCoordinates();

		if(!obj.element && this.options.display.indicateErrors != 0) {
			if (this.options.display.errorsLocation == 1) {
				var pos = (this.options.display.tipsPosition == 'left') ? coord.left : coord.right;
				var options = {
					'opacity' : 0,
					'position' : 'absolute',
					'float' : 'left',
					'left' : pos + this.options.display.tipsOffsetX
				};
				obj.element = new Element('div', {'class' : this.options.tipsClass, 'styles' : options}).injectInside(document.body);
				this.addPositionEvent(obj);
			} else if (this.options.display.errorsLocation == 2){
				obj.element = new Element('div', {'class' : this.options.errorClass, 'styles' : {'opacity' : 0}}).injectBefore(obj);
			} else if (this.options.display.errorsLocation == 3){
				obj.element = new Element('div', {'class' : this.options.errorClass, 'styles' : {'opacity' : 0}});
				if ($type(obj.group) == 'object' || $type(obj.group) == 'collection')
					obj.element.injectAfter(obj.group[obj.group.length-1]);
				else
					obj.element.injectAfter(obj);
			}
		}
		if (obj.element && obj.element != true) {
			obj.element.empty();
			if (this.options.display.errorsLocation == 1) {
				var errors = [];
				obj.errors.each(function(error) {
					errors.push(new Element('p').set('html', error));
				});
				var tips = this.makeTips(errors).injectInside(obj.element);
				if(this.options.display.closeTipsButton) {
					tips.getElements('a.close').addEvent('mouseup', function(){
						this.removeError(obj, 'tip');
					}.bind(this));
				}
				obj.element.setStyle('top', coord.top - tips.getCoordinates().height + this.options.display.tipsOffsetY);
			} else {
				obj.errors.each(function(error) {
					new Element('p').set('html',error).injectInside(obj.element);
				});
			}

			if (!this.options.display.fadeDuration || Browser.Engine.trident && Browser.Engine.version == 5 && this.options.display.errorsLocation < 2) {
				obj.element.setStyle('opacity', 1);
			} else {
				obj.fx = new Fx.Tween(obj.element, {
					'duration' : this.options.display.fadeDuration,
					'ignore' : true,
					'onStart' : function(){
						this.fxRunning = true;
					}.bind(this),
					'onComplete' : function() {
						this.fxRunning = false;
						if (obj.element && obj.element.getStyle('opacity').toInt() == 0) {
							obj.element.destroy();
							obj.element = false;
						}
					}.bind(this)
				});
				if(obj.element.getStyle('opacity').toInt() != 1) obj.fx.start('opacity', 1);
			}
		}
		if (this.options.display.addClassErrorToField && !obj.isChild){
			obj.addClass(this.options.fieldErrorClass);
			obj.element = obj.element || true;
		}

	},

	/*
	Function: addPositionEvent

		Update tips position after a browser resize
	*/
	addPositionEvent : function(obj) {
		if(this.options.display.replaceTipsEffect) {
			obj.event = function(){
				var coord = obj.target ? $(obj.target).getCoordinates() : obj.getCoordinates();
				new Fx.Morph(obj.element, {
					'duration' : this.options.display.fadeDuration
				}).start({
					'left':[obj.element.getStyle('left'), coord.right + this.options.display.tipsOffsetX],
					'top':[obj.element.getStyle('top'), coord.top - obj.element.getCoordinates().height + this.options.display.tipsOffsetY]
				});
			}.bind(this);

		} else {
			obj.event = function(){
				var coord = obj.target ? $(obj.target).getCoordinates() : obj.getCoordinates();
				obj.element.setStyles({
					'left':coord.right + this.options.display.tipsOffsetX,
					'top':coord.top - obj.element.getCoordinates().height + this.options.display.tipsOffsetY
				});
			}.bind(this);
		}
		window.addEvent('resize', obj.event);
	},

	/*
	Function: removeError
		Private method

		Remove the error display
	*/
	removeError : function(obj, method) {
		if ((this.options.display.addClassErrorToField && !obj.isChild && this.options.display.removeClassErrorOnTipClosure) || (this.options.display.addClassErrorToField && !obj.isChild && !this.options.display.removeClassErrorOnTipClosure && method != 'tip'))
			obj.removeClass(this.options.fieldErrorClass);

		if (!obj.element) return;
		this.alreadyIndicated = false;
		obj.errors = [];
		obj.isOK = true;
		window.removeEvent('resize', obj.event);
		if (this.options.display.errorsLocation >= 2 && obj.element) {
			new Fx.Tween(obj.element, {
				'duration': this.options.display.fadeDuration
			}).start('height', 0);
		}
		if (!this.options.display.fadeDuration || Browser.Engine.trident && Browser.Engine.version == 5 && this.options.display.errorsLocation == 1 && obj.element) {
			this.fxRunning = true;
			obj.element.destroy();
			obj.element = false;
			(function(){this.fxRunning = false}.bind(this)).delay(200);
		} else if (obj.element && obj.element != true) {
			obj.fx.start('opacity', 0);
		}
	},

	/*
	Function: focusOnError
		Private method

		Create set the focus to the first field with an error if needed
	*/
	focusOnError : function (obj) {
		if (this.options.display.scrollToFirst && !this.alreadyFocused && !this.isScrolling) {
			var dest; //moved this up to stop redclariations
			//This can changed to a single switch using default:
			if (!this.options.display.indicateErrors || !this.options.display.errorsLocation) {
				dest = obj.getCoordinates().top-30;
			} else {
				switch (this.options.display.errorsLocation){
					case 1 :
						dest = obj.element.getCoordinates().top;
						break;
					case 2 :
						dest = obj.element.getCoordinates().top-30;
						break;
					case 3 :
						dest = obj.getCoordinates().top-30;
						break;
				}
				this.isScrolling = true;
			}
			if (window.getScroll().y != dest) {
				new Fx.Scroll(window, {
					onComplete : function() {
						this.isScrolling = false;
						if (obj.getProperty('type') != 'hidden') obj.focus();
					}.bind(this)
				}).start(0,dest);
			} else {
				this.isScrolling = false;
				obj.focus();
			}
			this.alreadyFocused = true;
		}
	},

	/*
	Function: fixIeStuffs
		Private method

		Fix png for IE6
	*/
	fixIeStuffs : function () {
		if (Browser.Engine.trident4) {
			//We fix png stuffs
			var rpng = new RegExp('url\\(([\.a-zA-Z0-9_/:-]+\.png)\\)');
			var search = new RegExp('(.+)formcheck\.css');
			for (var i = 0; i < document.styleSheets.length; i++){
				if (document.styleSheets[i].href.match(/formcheck\.css$/)) {
					var root = document.styleSheets[i].href.replace(search, '$1');
					var count = document.styleSheets[i].rules.length;
					for (var j = 0; j < count; j++){
						var cssstyle = document.styleSheets[i].rules[j].style;
						var bgimage = root + cssstyle.backgroundImage.replace(rpng, '$1');
						if (bgimage && bgimage.match(/\.png/i)){
							var scale = (cssstyle.backgroundRepeat == 'no-repeat') ? 'crop' : 'scale';
							cssstyle.filter =  'progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, src=\'' + bgimage + '\', sizingMethod=\''+ scale +'\')';
							cssstyle.backgroundImage = "none";
						}
					}
				}
			}
		}
	},

	/*
	Function: makeTips
		Private method

		Create tips boxes
	*/
	makeTips : function(txt) {
		var table = new Element('table');
			table.cellPadding ='0';
			table.cellSpacing ='0';
			table.border ='0';

			var tbody = new Element('tbody').injectInside(table);
				var tr1 = new Element('tr').injectInside(tbody);
					new Element('td', {'class' : 'tl'}).injectInside(tr1);
					new Element('td', {'class' : 't'}).injectInside(tr1);
					new Element('td', {'class' : 'tr'}).injectInside(tr1);
				var tr2 = new Element('tr').injectInside(tbody);
					new Element('td', {'class' : 'l'}).injectInside(tr2);
					var cont = new Element('td', {'class' : 'c'}).injectInside(tr2);
						var errors = new Element('div', {'class' : 'err'}).injectInside(cont);
						txt.each(function(error) {
							error.injectInside(errors);
						});
						if (this.options.display.closeTipsButton) new Element('a',{'class' : 'close'}).injectInside(cont);
					new Element('td', {'class' : 'r'}).injectInside(tr2);
				var tr3 = new Element('tr').injectInside(tbody);
					new Element('td', {'class' : 'bl'}).injectInside(tr3);
					new Element('td', {'class' : 'b'}).injectInside(tr3);
					new Element('td', {'class' : 'br'}).injectInside(tr3);
		return table;
	},

	/*
	Function: reinitialize
		Reinitialize form before submit check. You can use this also to remove all tips from a form, passing the argument "forced" ( formcheck.reinitialize('forced'); )
	*/
	reinitialize: function(forced) {
		this.validations.each(function(el) {
			if (el.element) {
				el.errors = [];
				el.isOK = true;
				if(this.options.display.flashTips == 1 || forced == 'forced') {
					el.element.destroy();
					el.element = false;
				}
			}
		}, this);
		if (this.form.element) this.form.element.empty();
		this.alreadyFocused = false;
		this.firstError = false;
		this.elementToRemove = this.alreadyIndicated;
		this.alreadyIndicated = false;
		this.form.isValid = true;
	},

	/*
	Function: submitByAjax
		Private method

		Send the form by ajax, and replace the form with response
	*/

	submitByAjax: function() {
		this.fireEvent('ajaxRequest');
		new Request({
			url: this.form.action,
			method: this.form.method,
			data : this.form.toQueryString(),
			evalScripts: this.options.ajaxEvalScripts,
			onFailure: function(instance){
				this.fireEvent('ajaxFailure', instance);
			}.bind(this),
			onComplete: function(instance){
				this.fireEvent('ajaxComplete', instance);
			}.bind(this),
			onSuccess: function(result){
				this.fireEvent('ajaxSuccess', result);
				if(this.options.ajaxResponseDiv) $(this.options.ajaxResponseDiv).set('html',result);
			}.bind(this)
		}).send();
		return false;
	},

	/*
	Function: onSubmit
		Private method

		Perform check on submit action
	*/
	onSubmit: function(event) {
		this.reinitialize();
		this.fireEvent('onSubmit');
		
		this.validations.each(function(el) {
			var validation = this.manageError(el,'submit');
			if(!validation) this.form.isValid = false;
		}, this);

		if (this.form.isValid) {
			this.fireEvent('validateSuccess');
			//moved above to allow optional settings to this.form.submit and submitByAjax to be triggered by this option
			return (this.options.submitByAjax)? this.submitByAjax():this.options.submit;
			//if this.options.submit is false it can still rely on validateSuccess event
		} else {
			if (this.elementToRemove && this.elementToRemove != this.firstError && this.options.display.indicateErrors == 1) {
				this.removeError(this.elementToRemove);
			}
			this.focusOnError(this.firstError);
			this.fireEvent('validateFailure');
			return false;
		}
	}
});