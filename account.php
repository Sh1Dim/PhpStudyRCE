<?php

require_once __DIR__.'/../app/lib/common.php';
require_once __DIR__.'/../app/model/Account.php';

$type = post('type');
if(!$type){
	$type = get('type');
}
// 登录
if($type=='login'){
	$username = post('username');
	$username = preg_replace("/<(.*?)>/","",$username);
	$pwd = post('password');
	$verifycode = post('verifycode');
	$res = Account::login($username,$pwd,$verifycode);
	xpexit(json_encode($res));
}

// 退出登录
if($type == 'logout'){
	$res = Account::logout();
	xpexit(json_encode($res));
}

// 验证码
if(get('type')=='vercode'){
	require_once __DIR__.'/../app/lib/Vericode.php';
	VeriCode::create();
}

// 当前用户信息
if(get('type')=='admin_info'){
	require_once __DIR__.'/../app/model/Auth.php';
	Auth::doauth();
	xpexit(json_encode(array('code'=>0,'username'=>$_SESSION['admin']['username'])));
}

// 登录小皮
if($type=='loginxp'){
	require_once __DIR__.'/../app/model/Auth.php';
	$user = Auth::doauth();
	$ticket = trim(get('ticket'));
	$mid = get('mid');
	$req = json_encode(array('command'=>$type,'uid'=>$user['uid'],'mid'=>$mid,'ticket'=>$ticket));
	$res = Socket::request($req);
	$res = json_decode($res,true);

	xpexit(json_encode(array('code'=>0,'data'=>json_decode($res['data']))));
}

// 获取小皮帐号二维码
if($type=='xplogin_info'){
	require_once __DIR__.'/../app/model/Auth.php';
	$user = Auth::doauth();
	$req = json_encode(array('command'=>$type,'uid'=>$user['uid']));
	$res = Socket::request($req);
	$res = json_decode($res,true);
	if($res['result']==0){
		xpexit(json_encode(array('code'=>1,'msg'=>$res['msg'])));
	}
	xpexit(json_encode(array('code'=>0,'data'=>json_decode($res['data']['data']),'mid'=>$res['data']['mid'])));
}
// 检查小皮登录是否成功
if(get('type')=='checkxplogin'){
	require_once __DIR__.'/../app/model/Auth.php';
	$user = Auth::doauth();

	$mid = post('mid');
	$ticket = post('ticket');
	if($mid=='' || $ticket==''){
		return;
	}
	$req = json_encode(array('command'=>'check_xp_login','uid'=>$user['uid'],'mid'=>$mid,'ticket'=>$ticket));
	$res = Socket::request($req);
	$res = json_decode($res,true);
	
	if($res['result']==0){
		xpexit(json_encode(array('code'=>1,'msg'=>$res['msg'])));
	}
	if(isset($res['data']['uid'])&&$res['data']['uid']>0){
		$_SESSION['_xp_uid_'] = $res['data']['uid'];
		xpexit(json_encode(array('code'=>0,'xpuid'=>$_SESSION['_xp_uid_'])));
	}
	$res = json_decode($res['data'],true);
	
	if($res['code']>0){
		xpexit(json_encode($res));
	}
	xpexit(json_encode(array('code'=>0,'xpuid'=>(isset($_SESSION['_xp_uid_'])?$_SESSION['_xp_uid_']:0))));
}

// 同意用户使用协议
if(get('type')=='agree_user_agreement'){
	require_once __DIR__.'/../app/model/Auth.php';
	$user = Auth::doauth();
	$req_data = json_encode(array('command'=>$type,'uid'=>$user['uid']));
	$res = Socket::request($req_data);
	$res = json_decode($res,true);
	if($res['result']==0){
		xpexit(json_encode(array('code'=>1,'msg'=>$res['msg'])));
	}
	xpexit(json_encode(array('code'=>0,'data'=>$res['data']['AGREEMENT'])));
}