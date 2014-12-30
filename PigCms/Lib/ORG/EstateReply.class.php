<?php
class EstateReply {
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public $id;
	public function __construct($token, $wechat_id, $data, $siteUrl) {
		$this->item = M ( 'Estate' )->where ( array (
				'id' => $data ['pid'] 
		) )->find ();
		$this->id 		 = $data ['pid'];
		$this->wechat_id = $wechat_id;
		$this->siteUrl = $siteUrl;
		$this->token = $token;
	}
	public function index() {
		$thisItem = $this->item;
		$Estate=$thisItem;
		return array (
				array (
						array (
								$Estate ['title'],
								$Estate ['estate_desc'],
								$Estate ['cover'],
								$this->siteUrl . '/index.php?g=Wap&m=Estate&a=index&token=' . $this->token . '&wecha_id=' . $this->wechat_id . '&id=' . $this->id . '' 
						),
						array (
								'楼盘介绍',
								$Estate ['estate_desc'],
								$Estate ['house_banner'],
								$this->siteUrl . '/index.php?g=Wap&m=Estate&a=introduce&&token=' . $this->token . '&wecha_id=' . $this->wechat_id . '&id=' . $this->id . '' 
						),
						array (
								'楼盘相册',
								$Estate ['estate_desc'],
								$Estate ['banner'],
								$this->siteUrl . '/index.php?g=Wap&m=Estate&a=photo&token=' . $this->token . '&wecha_id=' . $this->wechat_id . '&id=' . $this->id . '' 
						),
						array (
								'专家点评',
								$Estate ['estate_desc'],
								$Estate ['cover'],
								$this->siteUrl . '/index.php?g=Wap&m=Estate&a=impress&&token=' . $this->token . '&wecha_id=' . $this->wechat_id . '&id=' . $this->id . '' 
						),
						array (
								'楼盘动态',
								$Estate ['estate_desc'],
								$Estate ['house_banner'],
								$this->siteUrl . '/index.php?g=Wap&m=Index&a=lists&classid=' . $Estate ['classify_id'] . '&token=' . $this->token . '&wecha_id=' . $this->wechat_id . '&id=' . $this->id . '' 
						) 
				),
				'news' 
		);
	}
}
?>

