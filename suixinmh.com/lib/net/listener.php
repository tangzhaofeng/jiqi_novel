<?php
class JieqiRequest_Listener extends JieqiObject {
var $_id;
function JieqiRequest_Listener()
{
$this->JieqiObject();
$this->_id = md5(uniqid('http_request_',1));
}
function getId()
{
return $this->_id;
}
function update(&$subject,$event,$data = NULL)
{
echo "Notified of event: '$event'\n";
if (NULL !== $data) {
echo "Additional data: ";
var_dump($data);
}
}
}
