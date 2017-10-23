FCKCommands.RegisterCommand( 'RemoteUpload'		, new FCKDialogCommand( FCKLang['DlgRemoteUploadTitle']	, FCKLang['DlgRemoteUploadTitle']		, FCKConfig.PluginsPath + 'remoteupload/remoteupload.html'	, 500, 400 ) ) ;

var oRemoteUploadItem		= new FCKToolbarButton( 'RemoteUpload', FCKLang['DlgRemoteUploadTitle'] ) ;
oRemoteUploadItem.IconPath	= FCKConfig.PluginsPath + 'remoteupload/remoteupload.gif' ;

FCKToolbarItems.RegisterItem( 'RemoteUpload', oRemoteUploadItem ) ;