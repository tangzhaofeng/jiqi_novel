<?php
/**
 * 数据池章节同步接口
 * <p>
 * 如果渠道的【章节】选择：数据池,那么需要实现此接口。
 * @author chengyuan
 *
 */
interface iSynchronization
{
	/**
	 * 不同的api同步章节时，会对章节的名称，内容等有不同的格式要求，通过此接口处理。
	 * <p>
	 * 根据api的需求实现此方法
	 * @param unknown $chapter		数据池章节数组（引用传递）
	 */
	public function handlePoolChapter(&$poolChapter);
}
?>