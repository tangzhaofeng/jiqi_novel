<?php
echo '<link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'style/about.css" type="text/css"  />
<!--wrap begin-->
<div class="wrap fix bg4">
  <!--sidebar3 begin-->
  <div class="sidebar3 fl">
   <ul class="list_menu2">
    <li id="index"';
if($this->_tpl_vars['_REQUEST']['method']=='index'){
echo ' class="thistab"';
}
echo '><a href="'.geturl('system','about','SYS=method=index').'">关于'.$this->_tpl_vars['jieqi_sitename'].' &gt;</a></li>
    <li id="business"';
if($this->_tpl_vars['_REQUEST']['method']=='business'){
echo ' class="thistab"';
}
echo '><a href="'.geturl('system','about','SYS=method=business').'">服务条款 &gt;</a></li>
    <li id="partner"';
if($this->_tpl_vars['_REQUEST']['method']=='partner'){
echo ' class="thistab"';
}
echo '><a href="'.geturl('system','about','SYS=method=partner').'">版权声明 &gt;</a></li>
    <li id="accession"';
if($this->_tpl_vars['_REQUEST']['method']=='accession'){
echo ' class="thistab"';
}
echo '><a href="'.geturl('system','about','SYS=method=accession').'">加入'.$this->_tpl_vars['jieqi_sitename'].' &gt;</a></li>
    <li id="contact"';
if($this->_tpl_vars['_REQUEST']['method']=='contact'){
echo ' class="thistab"';
}
echo '><a href="'.geturl('system','about','SYS=method=contact').'">联系我们 &gt;</a></li>
    <li id="friendly"';
if($this->_tpl_vars['_REQUEST']['method']=='friendly'){
echo ' class="thistab"';
}
echo '><a href="'.$this->_tpl_vars['jieqi_local_url'].'/link.html">友情链接 &gt;</a></li>    
   </ul>
   <div class="kf"></div>
  </div><!--sidebar3 end-->
  <!--article6 begin-->
  <div class="article6 fix mt20">
   <div class="adorn1"></div>
   <div class="adorn2"></div>
   <ul id="tab_conbox" class="about f0">';
if($this->_tpl_vars['_REQUEST']['method']=='index'){
echo '
    <li class="fix">
     <div class="boxtit rel"><p class="zi">关于'.$this->_tpl_vars['jieqi_sitename'].'</p><p class="zi2">ABOUT SHUHAI</p><span class="adorn"></span></div>
     <div class="intro f14">      <p>'.$this->_tpl_vars['jieqi_sitename'].'作为最具潜力的新锐小说网站，致力于打造一个全国最优秀的网络小说阅读平台。网站收录玄幻魔幻、仙侠修真、都市言情、历史军事、网游动漫、侦探推理、科幻恐怖、美文同人等各类文学作品，并支持小说在线阅读、手机电子书及各类格式下载。'.$this->_tpl_vars['jieqi_sitename'].'拥有丰富的资源和完备的功能，诚邀广大书友尽享饕餮盛宴！</p>
     </div>
    </li>';
}elseif($this->_tpl_vars['_REQUEST']['method']=='business'){
echo '
    <li class="fix">
     <div class="boxtit rel"><p class="zi">用户服务条款</p><p class="zi2">Service Clauses</p><span class="adorn"></span></div>
     <div class="intro f14"><p>本网站作为原创作品的上传空间网络服务提供者，所有作品均来自原创作者上传或作者书面授权。凡以任何方式登录本网站或直接、间接使用本网站资料者，视为自愿接受本网站服务条款的约束。</p><p>一、【适用范围】</p><p>本条款是您与'.$this->_tpl_vars['jieqi_sitename'].'网经营者之间关于用户使用'.$this->_tpl_vars['jieqi_sitename'].'网相关服务所订立的协议。'.$this->_tpl_vars['jieqi_sitename'].'网的经营者是指法律认可的经营'.$this->_tpl_vars['jieqi_sitename'].'网网站的责任主体。“用户”是指使用'.$this->_tpl_vars['jieqi_sitename'].'网相关服务的使用人，在本条款中更多地称为“您”。</p><p>本条款的适用范围包括'.$this->_tpl_vars['jieqi_sitename'].'网网站（'.$this->_tpl_vars['jieqi_local_url'].'）及相应Wap站点、移动App等。</p><p>二、【账号与密码安全】</p><p>2.1 您在使用'.$this->_tpl_vars['jieqi_sitename'].'网的服务时可能需要注册一个帐号。</p><p>2.2 '.$this->_tpl_vars['jieqi_sitename'].'网特别提醒您应妥善保管您的帐号和密码。当您使用完毕后，应安全退出。因您保管不善可能导致遭受盗号或密码失窃，责任由您自行承担。</p><p>2.3 '.$this->_tpl_vars['jieqi_sitename'].'网建议您使用的密码应至少包含以下四类字符中的三类：大写字母、小写字母、数字，以及键盘上的特殊符号，如果因为您设置的密码过于简单导致遭受盗号或密码失窃，责任由您自行承担。'.$this->_tpl_vars['jieqi_sitename'].'网对于非因'.$this->_tpl_vars['jieqi_sitename'].'网方单方原因所导致的任何账号安全、隐私保护等问题均不承担责任和所产生的各方损失。</p><p>三、【用户个人信息保护】</p><p>3.1 保护用户个人信息是'.$this->_tpl_vars['jieqi_sitename'].'网的一项基本原则。</p><p>3.2您在注册帐号或使用本服务的过程中，可能需要填写一些必要的信息。若国家法律法规有特殊规定的，您需要填写真实的身份信息。若您填写的信息不完整，则无法使用本服务或在使用过程中受到限制。</p><p>3.3 一般情况下，您可随时浏览、修改自己提交的信息，但出于安全性和身份识别（如账号申诉服务）的考虑，您可能无法修改注册时提供的初始注册信息及其他验证信息。</p><p>3.4 '.$this->_tpl_vars['jieqi_sitename'].'网将运用各种安全技术和程序建立完善的管理制度来保护您的个人信息，以免遭受未经授权的访问、使用或披露。</p><p>3.5 未经您的同意，'.$this->_tpl_vars['jieqi_sitename'].'网不会向'.$this->_tpl_vars['jieqi_sitename'].'网以外的任何公司、组织和个人披露您的个人信息，但法律法规另有规定的除外。</p><p>3.6 '.$this->_tpl_vars['jieqi_sitename'].'网非常重视对未成年人个人信息的保护。若父母（监护人）希望未成年人（尤其是十岁以下子女）得以使用'.$this->_tpl_vars['jieqi_sitename'].'网提供的服务，必须以父母（监护人）名义申请注册，并应以法定监护人身份对服务是否符合于未成年人加以判断。'.$this->_tpl_vars['jieqi_sitename'].'网保げ欢酝夤蛳虻谌脚痘蛱峁┯没ё⒉嶙柿霞坝没г谑褂猛绶袷贝娲⒃谑楹Ｍ衿骰蚴菘獾姆枪谌莺托畔ⅲ铝星榭龀猓?/p><p>(1) 事先获得用户的明确授权；</p><p>(2) 根据有关的法律法规要求；</p><p>(3) 按照相关政府主管部门和司法机关的要求；</p><p>(4) 为维护社会公众的利益；</p><p>(5) 为维护'.$this->_tpl_vars['jieqi_sitename'].'网的合法权益。</p><p>四、【广告服务】</p><p>4.1 您同意'.$this->_tpl_vars['jieqi_sitename'].'网可以在提供服务的过程中自行或由第三方广告商向您发送广告、推广或宣传信息（包括商业与非商业信息），其方式和范围可不经向您特别通知而变更。</p><p>4.2 '.$this->_tpl_vars['jieqi_sitename'].'网依照法律的规定对广告商履行相关义务，您应当自行判断广告信息的真实性并为自己的判断行为负责，除法律明确规定外，您因依该广告信息进行的交易或前述广告商提供的内容而遭受的损失或损害，'.$this->_tpl_vars['jieqi_sitename'].'网不承担责任。</p><p>4.3 您同意，对'.$this->_tpl_vars['jieqi_sitename'].'网服务中出现的广告信息，您应审慎判断其真实性和可靠性，除法律明确规定外，您应对依该广告信息进行的交易负责。</p><p>4.4 您同意，所有对您收取费用的服务或功能，均不能免除您接受'.$this->_tpl_vars['jieqi_sitename'].'网所提供的广告。因为这是'.$this->_tpl_vars['jieqi_sitename'].'网为所有用户提供综合服务的有效对价，您阅读本条款即视为对该规则的理解和接受。</p><p>五、【收费服务】</p><p>5.1 '.$this->_tpl_vars['jieqi_sitename'].'网的部分服务是以收费方式提供的，如您使用收费服务，请遵守相关的协议。'.$this->_tpl_vars['jieqi_sitename'].'网会在相关页面上做出提示，如果您拒绝支付该等费用，则不能使用相关的网络服务。</p><p>5.2 '.$this->_tpl_vars['jieqi_sitename'].'网将有权决定'.$this->_tpl_vars['jieqi_sitename'].'网所提供的服务的资费标准和收费方式，'.$this->_tpl_vars['jieqi_sitename'].'网可能会就不同的服务制定不同的资费标准和收费方式，也可能按照'.$this->_tpl_vars['jieqi_sitename'].'网所提供的服务的不同阶段确定不同的资费标准和收费方式。</p><p>5.3 '.$this->_tpl_vars['jieqi_sitename'].'网可能根据实际需要对收费服务的收费标准、方式进行修改和变更，'.$this->_tpl_vars['jieqi_sitename'].'网也可能会对部分免费服务开始收费。前述修改、变更或开始收费前，'.$this->_tpl_vars['jieqi_sitename'].'网将在相应服务页面进行通知或公告。如果您不同意上述修改、变更或付费内容，则应停止使用该服务。</p><p>六、【收益功能】</p><p>如果您在'.$this->_tpl_vars['jieqi_sitename'].'网使用任何创作发表作品（包括但不限于：小说、诗歌、书评等）的功能，则表明您愿意接受本网站用户对您所创作作品的打赏及催更等各类奖励或网站虚拟服务或产品奖励。我们在收到用户对您的奖励后，将扣除一定的渠道费，发放至您的账户。</p><p>如果您拒绝接受该等奖励或拒绝接受扣除渠道费，视为您不接受本协议内容和规则，则不能使用任何在本网站发表作品等各类相关服务和功能，本网站为保障其他用户正常使用网站服务和各项功能，除有权限制您上述功能和服务外，还有权中止或解除双方间的服务协议和法律关系。</p><p>七、【本服务中的软件】</p><p>7.1 您在使用本服务的过程中可能需要下载软件，对于这些软件，'.$this->_tpl_vars['jieqi_sitename'].'网给予您一项个人的、不可转让及非排他性的许可。您仅可为访问或使用本服务的目的而使用这些软件。</p><p>7.2 为了改善用户体验、保证服务的安全性及产品功能的一致性，'.$this->_tpl_vars['jieqi_sitename'].'网可能会对软件进行更新。您应该将相关软件更新到最新版本，否则'.$this->_tpl_vars['jieqi_sitename'].'网并不保证其能正常使用。</p><p>八、【知识产权声明】</p><p>8.1 '.$this->_tpl_vars['jieqi_sitename'].'网在本服务中所使用的商业标识，其著作权或商标权归'.$this->_tpl_vars['jieqi_sitename'].'网所有。</p><p>8.2 '.$this->_tpl_vars['jieqi_sitename'].'网所提供的服务与服务有关的全部信息、资料、文字、软件、声音、图片、视频、图表（包括相关的软件）的著作权、商标、商业秘密、其他知识产权、所有权或其他权利，均为'.$this->_tpl_vars['jieqi_sitename'].'网或其权利人所有，受中华人民共和国相关法律及中华人民共和国加入的国际协定和国际条约保护，除非事先获得'.$this->_tpl_vars['jieqi_sitename'].'网或其权利人的合法授权，您不得对任何该信息、资料、文字、软件、声音、图片、视频、图表进行修改、拷贝、散布、传送、展示、执行、复制、发行、授权、制作衍生著作、移转或销售。</p><p>8.3 如您未遵守本条款的上述规定，在不损害其他权利的情况下，'.$this->_tpl_vars['jieqi_sitename'].'网可立即终止向您提供服务，您必须销毁任何已经获得的上述信息、资料、文字、软件、声音、图片、视频、图表。违者视为您的严重违约，'.$this->_tpl_vars['jieqi_sitename'].'网可以中止对您的服务，解除双方间的服务协议和法律关系，且无需退还您所支付的费用，视为您支付'.$this->_tpl_vars['jieqi_sitename'].'网的违约金，如违约金不足以弥补'.$this->_tpl_vars['jieqi_sitename'].'网的损失的，'.$this->_tpl_vars['jieqi_sitename'].'网还可通过其他法律途径向您追索。</p><p>九、【用户违法行为】</p><p>9.1 您在使用本服务时须遵守法律法规，不得利用本服务从事违法违规行为，包括但不限于：</p><p>(1) 发布、传送、传播、储存危害国家安全统一、破坏社会稳定、违反公序良俗、侮辱、诽谤、淫秽、暴力以及任何违反国家法律法规的内容；</p><p>(2) 发布、传送、传播、储存侵害他人知识产权、商业秘密等合法权利的内容；</p><p>(3) 恶意虚构事实、隐瞒真相以误导、欺骗他人；</p><p>(4) 发布、传送、传播广告信息及垃圾信息；</p><p>(5) 其他法律法规禁止的行为。</p><p>9.2 如果您违反了本条约定，相关国家机关或机构可能会对您提起诉讼、罚款或采取其他制裁措施，并要求'.$this->_tpl_vars['jieqi_sitename'].'网给予协助。造成损害的，您应依法予以赔偿，'.$this->_tpl_vars['jieqi_sitename'].'网不承担任何责任。</p><p>9.3 如果'.$this->_tpl_vars['jieqi_sitename'].'网发现或收到他人举报您发布的信息违反本条约定，'.$this->_tpl_vars['jieqi_sitename'].'网有权进行独立判断并采取技术手段予以删除、屏蔽或断开链接。同时，'.$this->_tpl_vars['jieqi_sitename'].'网有权视用户的行为性质，采取包括但不限于暂停或终止服务，限制、冻结或终止账号使用，追究法律责任等措施。</p><p>9.4 您违反本条约定，导致任何第三方损害的，您应当独立承担责任；'.$this->_tpl_vars['jieqi_sitename'].'网因此遭受损失的，您也应当一并赔偿。</p><p>9.5 违反本条约定，视为您的严重违约，'.$this->_tpl_vars['jieqi_sitename'].'网可以中止对您的服务，解除双方间的服务协议和法律关系，且无需退还您所支付的费用，视为您支付'.$this->_tpl_vars['jieqi_sitename'].'网的违约金，如违约金不足以弥补'.$this->_tpl_vars['jieqi_sitename'].'网的损失的，'.$this->_tpl_vars['jieqi_sitename'].'网还可通过其他法律途径向您追索。</p><p>十、【用户发送、传播的内容与第三方投诉处理】</p><p>10.1 您通过本服务发送或传播的内容（包括但不限于网页、文字、图片、音频、视频、图表等）均由您自行承担责任。</p><p>10.2 您发送或传播的内容应有合法来源，相关内容为您所有或您已获得权利人的授权。</p><p>10.3 您同意'.$this->_tpl_vars['jieqi_sitename'].'网可为履行本协议或提供本服务的目的而使用您发送或传播的内容。</p><p>10.4 如果'.$this->_tpl_vars['jieqi_sitename'].'网收到权利人通知，主张您发送或传播的内容侵犯其相关权利的，您同意'.$this->_tpl_vars['jieqi_sitename'].'网有权进行独立判断并采取删除、屏蔽或断开链接等措施。</p><p>十一、【不可抗力及其他免责事由】</p><p>11.1 您理解并同意，在使用本服务的过程中，可能会遇到不可抗力等风险因素，使本服务发生中断。不可抗力是指不能预见、不能克服并不能避免且对一方或双方造成重大影响的客观事件，包括但不限于自然灾害如洪水、地震、瘟疫流行和风暴等以及社会事件如战争、动乱、政府行为等。出现上述情况时，'.$this->_tpl_vars['jieqi_sitename'].'网将努力在第一时间与相关单位配合，及时进行修复，但是由此给您造成的损失'.$this->_tpl_vars['jieqi_sitename'].'网在法律允许的范围内免责。</p><p>11.2 在法律允许的范围内，'.$this->_tpl_vars['jieqi_sitename'].'网对以下情形导致的服务中断或受阻不承担责任：</p><p>(1) 受到计算机病毒、木马或其他恶意程序、黑客攻击的破坏；</p><p>(2) 用户或'.$this->_tpl_vars['jieqi_sitename'].'网的电脑软件、系统、硬件和通信线路出现故障；</p><p>(3) 用户操作不当；</p><p>(4) 用户通过非'.$this->_tpl_vars['jieqi_sitename'].'网授权的方式使用本服务；</p><p>(5) 其他'.$this->_tpl_vars['jieqi_sitename'].'网无法控制或合理预见的情形。</p><p>11.3 您理解并同意，在使用本服务的过程中，可能会遇到网络信息或其他用户行为带来的风险，'.$this->_tpl_vars['jieqi_sitename'].'网不对任何信息的真实性、适用性、合法性承担责任，也不对因侵权行为给您造成的损害负责。这些风险包括但不限于：</p><p>(1) 来自他人匿名或冒名的含有威胁、诽谤、令人反感或非法内容的信息；</p><p>(2) 因使用本协议项下的服务，遭受宋蟮肌⑵燮蚱渌贾禄蚩赡艿贾碌娜魏涡睦怼⑸砩系纳撕σ约熬蒙系乃鹗В?
(3) 其他因网络信息或用户行为引起的风险。</p><p>11.4 您理解并同意，本服务并非为某些特定目的而设计，包括但不限于核设施、军事用途、医疗设施、交通通讯等重要领域。如果因为软件或服务的原因导致上述操作失败而带来的人员伤亡、财产损失和环境破坏等，'.$this->_tpl_vars['jieqi_sitename'].'网不承担法律责任。</p><p>11.5 '.$this->_tpl_vars['jieqi_sitename'].'网依据本协议约定获得处理违法违规内容的权利，该权利不构成'.$this->_tpl_vars['jieqi_sitename'].'网的义务或承诺，'.$this->_tpl_vars['jieqi_sitename'].'网不能保证及时发现违法行为或进行相应处理。</p><p>11.6 在任何情况下，您不应轻信借款、索要密码或其他涉及财产的网络信息。涉及财产操作的，请一定先核实对方身份，并请经常留意'.$this->_tpl_vars['jieqi_sitename'].'网有关防范诈骗犯罪的提示。</p><p>十二、【条款的生效与变更】</p><p>12.1 您使用'.$this->_tpl_vars['jieqi_sitename'].'网的服务即视为您已阅读本条款并接受本条款的约束。</p><p>12.2 '.$this->_tpl_vars['jieqi_sitename'].'网有权在必要时修改本条款。您可以在相关服务页面查阅最新版本的服务条款。</p><p>12.3 本服务条款变更后，如果您继续使用'.$this->_tpl_vars['jieqi_sitename'].'网提供的软件或服务，即视为您已接受修改后的协议。如果您不接受修改后的协议，应当停止使用'.$this->_tpl_vars['jieqi_sitename'].'网提供的软件或服务。</p><p>十三、【服务的变更、中断、终止】</p><p>13.1 '.$this->_tpl_vars['jieqi_sitename'].'网可能会对服务内容进行变更，也可能会中断、中止或终止服务。</p><p>13.2 如发生下列任何一种情形，'.$this->_tpl_vars['jieqi_sitename'].'网有权不经通知而中断或终止向您提供的服务：</p><p>(1) 根据法律规定您应提交真实信息，而您提供的个人资料不真实、或与注册时信息不一致又未能提供合理证明；</p><p>(2) 您违反相关法律法规或本协议的约定；</p><p>(3) 按照法律规定或主管部门的要求；</p><p>(4) 出于安全的原因或其他必要的情形。</p><p>十四、【管辖与法律适用】</p><p>14.1 本协议的成立、生效、履行、解释及纠纷解决，适用中华人民共和国大陆地区法律（不包括冲突法）。</p><p>14.2 若您和'.$this->_tpl_vars['jieqi_sitename'].'网之间发生任何纠纷或争议，首先应友好协商解决；协商不成的，您同意将纠纷或争议提交本协议签订地有管辖权的人民法院管辖。</p><p>14.3 本协议所有条款的标题仅为阅读方便，本身并无实际涵义，不能作为本协议涵义解释的依据。</p><p>14.4本协议条款无论因何种原因部分无效或不可执行，其余条款仍有效，对双方具有约束力。</p><p>十五、【未成年人使用条款】</p><p>15.1 若用户未满18周岁，则为未成年人，应在监护人监护、指导下阅读本协议和使用本服务。</p><p>15.2 未成年人用户涉世未深，容易被网络虚象迷惑，且好奇心强，遇事缺乏随机应变的处理能力，很容易被别有用心的人利用而又缺乏自我保护能力。因此，未成年人用户在使用本服务时应注意以下事项，提高安全意识，加强自我保护：</p><p>(1) 认清网络世界与现实世界的区别，避免沉迷于网络，影响日常的学习生活；</p><p>(2) 填写个人资料时，加强个人保护意识，以免不良分子对个人生活造成骚扰；</p><p>(3) 在监护人或老师的指导下，学习正确使用网络；</p><p>(4) 避免陌生网友随意会面或参与联谊活动，以免不法分子有机可乘，危及自身安全。</p><p>15.3 监护人、学校均应对未成年人使用本服务时多做引导。特别是家长应关心子女的成长，注意与子女的沟通，指导子女上网应该注意的安全问题，防患于未然。若父母（监护人）希望未成年人（尤其是十岁以下子女）得以使用'.$this->_tpl_vars['jieqi_sitename'].'网提供的服务，必须以父母（监护人）名义申请注册，并应以法定监护人身份对服务是否符合于未成年人加以判断。</p><p>十六、【其他】</p><p>'.$this->_tpl_vars['jieqi_sitename'].'网拥有本用户服务条款最终解释权，如果您对本协议或本服务有意见或建议，可与'.$this->_tpl_vars['jieqi_sitename'].'网网站客服联系（'.$this->_tpl_vars['jieqi_local_url'].'），我们会给予您必要的帮助。</p>
     </div>
    </li>';
}elseif($this->_tpl_vars['_REQUEST']['method']=='partner'){
echo '
    <li class="fix">
     <div class="boxtit rel"><p class="zi">版权声明</p><p class="zi2">Copyright Statement</p><span class="adorn"></span></div>
     <div class="intro f14"><p>本站作为原创作品的上传空间网络服务提供者，'.$this->_tpl_vars['jieqi_sitename'].'网仅为用户提供本人原创作品的上传空间服务，不会对用户上传的作品内容作任何形式的编辑修改，亦不会从该类作品提供者的发布行为中获取经济利益。</p><br /><p>本站一贯高度重视知识产权保护并遵守中华人民共和国各项知识产权法律、法规和具有约束力的规范性文件,坚信著作权拥有者的合法权益应该得到尊重和依法保护。'.$this->_tpl_vars['jieqi_sitename'].'网坚决反对任何违反中华人民共和国相关版权法律法规的行为，请各位用户务必上传自己的原创作品，切勿侵犯他人的知识产权及相关权利。如果广大网友发现用户上传的某部作品有侵犯他人权利的情形，欢迎来函告知。</p><br /><p>由于我们无法对用户上传到本网站的所有作品内容进行充分的监测，我们制定了旨在保护知识产权权利人合法权益的措施和步骤，当著作权人和/或依法可以行使信息网络传播权的权利人发现本站上用户上传内容侵犯其信息网络传播权时，权利人应事先向本站发出权利通知，并提供相应证明材料。本站将根据相关法律规定采取措施删除相关内容。</p><br /><p>为提高本站工作效率，及时停止发布侵权作品，提出侵权指控者必须提供以下材料：</p><p>1、权利人的身份证明：包括身份证、法人执照、营业执照等有效身份证件；</p><p>2、权利人的权属证明：对于涉嫌侵权作品拥有著作权或依法可以行使信息网络传播权的权属证明，包括但不限于有关的著作权登记证书或创作原手稿等；</p><p>3、侵权情况证明：包括被控侵权信息的内容的作品名称、作者以及在本网站的地址；</p><p>4、权利人的联系方式（包括电话、Email地址等）；</p><p>5、侵权材料内容须由权利人亲笔签名确认。</p><br />相关材料投寄地址：<p>陕西省西安市浐灞生态区浐灞大道一号浐灞商务中心二期三层</p><p>邮编 710024</p><p>陕西'.$this->_tpl_vars['jieqi_sitename'].'网络科技有限公司 收</p><br /><p>本站在收到上述通知后会发送电子邮件通知上传该等作品的用户。对于多次上传涉嫌侵权作品的用户，我们将取消其用户资格。</p><br /><p>一经本站查实的侵权作品，我们将立即采取包括删除作品等措施。如不符合上述条件，我们会请权利人补充提供相应信息，且暂不采取包括删除等相应措施。</p><br /><p>在本站上传作品的用户视为同意本站上述及已采用的相应措施。'.$this->_tpl_vars['jieqi_sitename'].'网对他人在网站上实施的此类侵权行为不承担任何违约责任或其他法律责任，一切法律责任概由上传人承担。'.$this->_tpl_vars['jieqi_sitename'].'网采取删除等相应措施后不因此而承担任何违约责任或其他任何法律责任，包括不承担因侵权指控不成立而给原上传用户带来损害的赔偿责任。</p>
     </div>
    </li>';
}elseif($this->_tpl_vars['_REQUEST']['method']=='accession'){
echo '
    <li class="fix">

    </li>';
}elseif($this->_tpl_vars['_REQUEST']['method']=='contact'){
echo '
    <li class="fix">
     <div class="boxtit rel"><p class="zi">联系我们</p><p class="zi2">Contact Us</p><span class="adorn"></span></div>
     <div class="intro f14">1.客服（读者、作者）<p>微信公众号 pinshunet</p><p>QQ：724171887</p>
     </div>
    </li>';
}else{
echo '
    <li class="fix">
     <div class="boxtit rel"><p class="zi">友情链接</p><p class="zi2">Friendly Link</p><span class="adorn"></span></div>
     <div class="intro flink f12 f_gray3 g9"><a target="_blank" href="http://www.tyread.com/">天翼阅读</a>     </div>
    </li>';
}
echo ' 
   </ul>
  </div><!--article6 end-->
</div><!--wrap end-->
';
?>