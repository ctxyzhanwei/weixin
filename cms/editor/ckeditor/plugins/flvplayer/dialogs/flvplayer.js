CKEDITOR.dialog.add('flvplayer',function(editor){
	var escape=function(value){
		return value;
		};
		return {
			title:'插入Flv视频',
			resizable:CKEDITOR.DIALOG_RESIZE_BOTH,
			minWidth:350,
			minHeight:300,
			contents:[{
				id: 'info',    
                    label: '常规',  
                    accessKey: 'P',  
                    elements:[  
                        {  
                        type: 'hbox',  
                        widths : [ '80%', '20%' ],  
                        children:[{  
                                id: 'src',  
                                type: 'text',  
                                label: '视频文件'  
                            },{  
                                type: 'button',  
                                id: 'browse',  
                                filebrowser: 'info:src',  
                                hidden: true,  
                                align: 'center',  
                                label: '浏览服务器'  
                            }]  
                        }, 
                        {  
                        type: 'hbox',  
                        widths : [ '80%', '20%' ],  
                        children:[{  
                                id: 'preview',  
                                type: 'text',  
                                label: '预览图'  
                            },{  
                                type: 'button',  
                                id: 'browse_preview',  
                                filebrowser: 'info:preview',  
                                hidden: true,  
                                align: 'center',  
                                label: '浏览服务器'  
                            }]  
                        }, 
                        {  
                        type: 'hbox',  
                        widths : [ '35%', '35%', '30%' ],  
                        children:[{  
                            type:'text',
                            label:'视频宽度',
                            id:'mywidth',
                            'default':'470px',
                            style:'width:50px'  
                        },{
                        	type:'text',
                        	label:'视频高度',  
                        	id:'myheight',
                        	'default':'320px',
                        	style:'width:50px'  
                        },{  
                            type:'select',
                            label:'',
                            id:'myloop',  
                            required:true,
                            'default':'false',
                            style:'display:none',
                            items:[['是','true'],['否','false']]  
                        }]//children finish   
                        }]  
                    }, {  
                        id: 'Upload',  
                        hidden: true,  
                        filebrowser: 'uploadButton',  
                        label: '上传视频',  
                        elements: [{  
                            type: 'file',  
                            id: 'upload',  
                            label: '上传',  
                            size:38  
                        },  
                        {  
                            type: 'fileButton',  
                            id: 'uploadButton',  
                            label: '发送到服务器',  
                            filebrowser: 'info:src',  
                            'for': ['Upload', 'upload']//'page_id', 'element_id'    
                        }]
                        }],  
                        onOk:function(){
                        	mywidth=this.getValueOf('info','mywidth');  
myheight=this.getValueOf('info','myheight');  
//myloop=this.getValueOf('info','myloop'); 
myloop="true"; 
mysrc=this.getValueOf('info','src');
preview=this.getValueOf('info','preview');  
html=''+escape(mysrc)+'';  
//editor.insertHtml("<preclass=/"brush:"+lang+";/">"+html+"</pre>");   
editor.insertHtml("<object id=\"player\" classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0\" width=\""+mywidth+"\" height=\""+myheight+"\"><param name=\"name\" value=\"player\"><param name=\"autostart\" value=\""+myloop+"\"><param name=\"allowfullscreen\" value=\"true\"><param name=\"allowscriptaccess\" value=\"always\"><param name=\"flashvars\" value=\"file="+html+"&image="+preview+"\"><param name=\"src\" value=\"/js/jwplayer/jwplayer.swf\"><embed id=\"player\" type=\"application/x-shockwave-flash\" src=\"/js/jwplayer/jwplayer.swf\" flashvars=\"file="+html+"&image="+preview+"\" allowscriptaccess=\"always\" allowfullscreen=\"true\" name=\"player\" autostart=\""+myloop+"\" width=\""+mywidth+"\" height=\""+myheight+"\"></object>");  
},  
onLoad:function(){  
}  
};  
});  