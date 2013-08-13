<div class="span6 offset3">
    <div class="alert alert-info">
        <p>上传图片大小不超过800Kb</p>
        <p>图片最大: 3600 * 1800</p>
    </div>
<?php 
$error = trim(validation_errors().$this->upload->display_errors());
if(!empty($error)){
    echo '<div class="alert alert-error">';
    echo validation_errors();
    echo $this->upload->display_errors();
    echo '</div>';
}?>
<?php echo form_open_multipart('show/joinin',array('class'=>'form-horizontal'));?>
<div class="control-group">
    <input type="text" name="bbs_nickname" class="input-block-level" value="<?php echo set_value('bbs_nickname'); ?>" placeholder="称呼(必填)" required>
</div>
<div class="control-group">
    <textarea name="description" id="description" rows="3" placeholder="你想要说的话...(必填)" class="input-block-level" required><?php echo set_value('description'); ?></textarea>
</div>
<div class="control-group"><input type="file" name="image" id="image" required></div>
<fieldset>
    <legend>联系方式:</legend>
    <p class="text-error">联系方式至少填写一项,如果获奖,将通过以下所填写三种方式中一种进行通知.如不填则获奖作废.</p>
    <p class="text-error">填写微博请关注官方微博,以免不能私信或者@. </p>
    <p class="text-error">填写微信昵称,请关注官方微信.<img src="<?php echo base_url('asset/images/weixin.jpg');?>" /></p>
    <div class="control-group">
        <input type="url" class="input-block-level" name="weibo_url" placeholder="个人微博地址,格式: http://www.weibo.com/****** " />
    </div>    
    <div class="control-group">
        <input type="text" name="weixin_nickname" class="input-block-level" placeholder="微信昵称或者微信号" />
    </div>    
    <div class="control-group">
        <input type="text" name="phone" class="input-block-level" placeholder="手机号" />
    </div>
</fieldset>
<div class="form-actions">
  <button type="submit" class="btn btn-primary">提交</button>
  <a href="<?php echo site_url('show/'); ?>" class="btn">取消</a>
</div>
</form>
</div>