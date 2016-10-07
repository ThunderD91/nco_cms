<?php
class DataHandler extends DB{

    function __construct(){
        parent::__construct();
    }
    function createPage($post){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $url=$this->esc($post['url_key']);
        $title=$this->esc($post['title']);
        $robot=$this->esc($post['meta_robots']);
        $desc= empty($post['meta_description']) ? 'NULL' : "'".$this->esc($post['meta_description'])."'";
        $requireUrl=$this->find('pages',array('cond'=>'page_url_key=""'));
        $req=count($requireUrl) > 0 ? true : false;
        if((empty($url) && $req) || empty($title) || empty($robot)) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $this->find('pages', array('cond' => "page_url_key='$url'"));
            if ($this->row_totals > 0) {
                alert('warning', URL_NOT_AVAILABLE);
            }else {
                $insert = $this->execute("INSERT INTO pages (page_url_key,page_title,page_meta_robots,page_meta_description) VALUES ('$url','$title','$robot',$desc);", true);
                if (count($this->find('pages', array('cond' => "page_id=$insert"))) > 0) {
                    $result['data']['inserted_id'] = $insert;
                    $result['success'] = true;
                    alert('success', ITEM_CREATED . ' <a href="index.php?page=pages" data-page="pages">' . RETURN_TO_OVERVIEW . '</a>');
                }
            }
        }
        return $result;
    }
    function editPage($post){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $id=$this->esc($post['pageid']);
        $url=$this->esc($post['url_key']);
        $title=$this->esc($post['title']);
        $robot=$this->esc($post['meta_robots']);
        $desc= empty($post['meta_description']) ? 'NULL' : "'".$this->esc($post['meta_description'])."'";
        $requireUrl=$this->find('pages',array('cond'=>'page_url_key="" AND page_id!='.$id));
        $req=count($requireUrl) > 0 ? true : false;
        if((empty($url) && $req) || empty($title) || empty($robot)) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $this->find('pages', array('cond' => "page_url_key='$url' AND page_id!=$id"));
            if ($this->row_totals > 0) {
                alert('warning', URL_NOT_AVAILABLE);
            }else {
                $this->execute("UPDATE pages SET page_url_key='$url',page_title='$title',page_meta_robots='$robot',page_meta_description=$desc WHERE page_id=$id;");
                if(!$this->last_err) {
                    $result['success'] = true;
                    alert('success', ITEM_UPDATED . ' <a href="index.php?page=pages" data-page="pages">' . RETURN_TO_OVERVIEW . '</a>');
                }else{
                    alert('warning', sprintf(UPDATE_FAILED, PAGE) . ' <a href="index.php?page=pages" data-page="pages">' . RETURN_TO_OVERVIEW . '</a>');
                }
            }
        }
        return $result;
    }
    function createPageContent($post,$page_id){

        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $content_type=$this->esc($post['content_type']);
        $layout=$this->esc($post['layout']);

        $pagefunction=$content_type == '2' ? "'".$this->esc($post['page_function'])."'" : 'NULL';
        $desc=$content_type == '1' ? "'".$this->esc($post['description'])."'" : 'NULL';
        $content=$content_type == '1' ? "'".$this->esc($post['content'])."'" : 'NULL';


        if(empty($content_type) || empty($layout) || ($pagefunction != 'NULL' && empty($pagefunction)) || ($desc != 'NULL' && empty($desc))
            || ($content != 'NULL' && empty($content))) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $count=$this->find('page_content',array('fields'=>'count(page_content_id) as new_order','cond'=>"fk_page_id=$page_id"));
            $newCount= count($count) > 0 ? intval($count[0]['new_order'])+1: 1;
            $insert = $this->execute("INSERT INTO page_content (fk_page_function_id,fk_page_layout_id,page_content_description,page_content,page_content_type,fk_page_id,page_content_order) VALUES ($pagefunction,$layout,$desc,$content,$content_type,$page_id,$newCount);", true);

            if (count($this->find('page_content', array('cond' => "page_content_id=$insert"))) > 0) {
                $result['data']['inserted_id'] = $insert;
                $result['success'] = true;
                alert('success', ITEM_CREATED . ' <a href="index.php?page=page-content&page-id='.$page_id.'" data-page="page-content" data-params="page-id='.$page_id.'">' . RETURN_TO_OVERVIEW . '</a>');
            }

        }
        return $result;
    }
    function editPageContent($post,$page_id){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $id=$this->esc($post['contentid']);

        $content_type=$this->esc($post['content_type']);
        $layout=$this->esc($post['layout']);

        $pagefunction=$content_type == '2' ? "'".$this->esc($post['page_function'])."'" : 'NULL';
        $desc=$content_type == '1' ? "'".$this->esc($post['description'])."'" : 'NULL';
        $content=$content_type == '1' ? "'".$this->esc($post['content'])."'" : 'NULL';


        if(empty($content_type) || empty($layout) || ($pagefunction != 'NULL' && empty($pagefunction)) || ($desc != 'NULL' && empty($desc))
            || ($content != 'NULL' && empty($content))) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $this->execute("UPDATE page_content SET fk_page_function_id=$pagefunction,fk_page_layout_id=$layout,page_content_description=$desc,page_content_type=$content_type WHERE fk_page_id=$page_id AND page_content_id=$id;");
            if(!$this->last_err) {
                $result['success'] = true;
                alert('success', ITEM_UPDATED . ' <a href="index.php?page=page-content&page-id='.$page_id.'" data-page="page-content" data-params="page-id='.$page_id.'">' . RETURN_TO_OVERVIEW . '</a>');
            }else{
                alert('warning', sprintf(UPDATE_FAILED, PAGE_CONTENT) . ' <a href="index.php?page=page-content&page-id='.$page_id.'" data-page="page-content" data-params="page-id='.$page_id.'">' . RETURN_TO_OVERVIEW . '</a>');
            }

        }
        return $result;
    }
    function createPost($post,$userid){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $url=$this->esc($post['url_key']);
        $title=$this->esc($post['title']);
        $content=$this->esc($post['content']);
        $desc= empty($post['meta_description']) ? 'NULL' : "'".$this->esc($post['meta_description'])."'";
        if(empty($url) || empty($title)) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $this->find('posts', array('cond' => "post_url_key='$url'"));
            if ($this->row_totals > 0) {
                alert('warning', URL_NOT_AVAILABLE);
            }else {
                $insert = $this->execute("INSERT INTO posts (post_url_key,post_title,post_content,post_meta_description,fk_user_id) VALUES ('$url','$title','$content',$desc,$userid);", true);
                if (count($this->find('posts', array('cond' => "post_id=$insert"))) > 0) {
                    $result['data']['inserted_id'] = $insert;
                    $result['success'] = true;
                    alert('success', ITEM_CREATED . ' <a href="index.php?page=posts" data-page="posts">' . RETURN_TO_OVERVIEW . '</a>');
                }
            }
        }
        return $result;
    }
    function editPost($post){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $id=$this->esc($post['postid']);
        $url=$this->esc($post['url_key']);
        $title=$this->esc($post['title']);
        $content=$this->esc($post['content']);
        $desc= empty($post['meta_description']) ? 'NULL' : "'".$this->esc($post['meta_description'])."'";
        if(empty($url) || empty($title)) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $this->find('posts', array('cond' => "post_url_key='$url' AND post_id!=$id"));
            if ($this->row_totals > 0) {
                alert('warning', URL_NOT_AVAILABLE);
            }else {
                $this->execute("UPDATE posts SET post_url_key='$url',post_title='$title',post_content='$content',post_meta_description=$desc WHERE post_id=$id;");
                if(!$this->last_err) {
                    $result['success'] = true;
                    alert('success', ITEM_UPDATED . ' <a href="index.php?page=posts" data-page="posts">' . RETURN_TO_OVERVIEW . '</a>');
                }else{
                    alert('warning', sprintf(UPDATE_FAILED, BLOG_POSTS) . ' <a href="index.php?page=posts" data-page="posts">' . RETURN_TO_OVERVIEW . '</a>');
                }
            }
        }
        return $result;
    }
    function createMenuLink($post,$menuid){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $name=$this->esc($post['name']);
        $link_type=$this->esc($post['link_type']);
        $page= $this->esc($post['page']);
        $ipost= empty($post['post']) ? 'NULL' : "'".$this->esc($post['post'])."'";

        if(empty($name) || empty($link_type) || empty($page) || (empty($ipost) && $ipost!='NULL')) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $count=$this->find('menu_links',array('fields'=>'count(menu_link_id) as new_order','cond'=>"fk_menu_id=$menuid"));
            $newCount= count($count) > 0 ? intval($count[0]['new_order'])+1: 1;
            $insert = $this->execute("INSERT INTO menu_links (menu_link_order,menu_link_name,menu_link_type,fk_page_id,fk_post_id,fk_menu_id) VALUES ($newCount,'$name',$link_type,$page,$ipost,$menuid);", true);
            if (count($this->find('menu_links', array('cond' => "menu_link_id=$insert"))) > 0) {
                $result['data']['inserted_id'] = $insert;
                $result['success'] = true;
                alert('success', ITEM_CREATED . ' <a href="index.php?page=menu-links&menu-id='.$menuid.'" data-page="menu-links" data-params="menu-id='.$menuid.'">' . RETURN_TO_OVERVIEW . '</a>');
            }
        }
        return $result;
    }
    function editMenuLink($post,$menuid){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $id=$this->esc($post['menulinkid']);

        $name=$this->esc($post['name']);
        $link_type=$this->esc($post['link_type']);
        $page= $this->esc($post['page']);
        $ipost= empty($post['post']) ? 'NULL' : "'".$this->esc($post['post'])."'";

        if(empty($name) || empty($link_type) || empty($page) || (empty($ipost) && $ipost!='NULL')) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $this->execute("UPDATE menu_links SET menu_link_name='$name',menu_link_type=$link_type,fk_page_id=$page,fk_post_id=$ipost,fk_menu_id=$menuid WHERE menu_link_id=$id;");
            if(!$this->last_err) {
                $result['success'] = true;
                alert('success', ITEM_UPDATED . ' <a href="index.php?page=menu-links&menu-id='.$menuid.'" data-page="menu-links" data-params="menu-id='.$menuid.'">' . RETURN_TO_OVERVIEW . '</a>');
            }else{
                alert('warning', sprintf(UPDATE_FAILED, MENU_LINKS) . ' <a href="index.php?page=menu-links&menu-id='.$menuid.'" data-page="menu-links" data-params="menu-id='.$menuid.'">' . RETURN_TO_OVERVIEW . '</a>');
            }

        }
        return $result;
    }
    function createPostComment($post,$post_id,$user_id){

        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $content=$this->esc($post['content']);

        if(empty($content)) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $insert = $this->execute("INSERT INTO post_comments (comment_content,fk_post_id,fk_user_id) VALUES ('$content',$post_id,$user_id);", true);

            if (count($this->find('post_comments', array('cond' => "comment_id=$insert"))) > 0) {
                $result['data']['inserted_id'] = $insert;
                $result['success'] = true;
                alert('success', ITEM_CREATED . ' <a href="index.php?page=comments&post-id='.$post_id.'" data-page="comments" data-params="post-id='.$post_id.'">' . RETURN_TO_OVERVIEW . '</a>');
            }

        }
        return $result;
    }
    function editPostComment($post,$post_id){
        $result=array(
            'data'=>$post,
            'success'=>false
        );
        $id=$this->esc($post['commentid']);

        $content=$this->esc($post['content']);

        if(empty($content)) {
            alert('warning',REQUIRED_FIELDS_EMPTY);
        }else{
            $this->execute("UPDATE post_comments SET comment_content='$content' WHERE fk_post_id=$post_id AND comment_id=$id;");
            if(!$this->last_err) {
                $result['success'] = true;
                alert('success', ITEM_UPDATED . ' <a href="index.php?page=comments&post-id='.$post_id.'" data-page="comments" data-params="post-id='.$post_id.'">' . RETURN_TO_OVERVIEW . '</a>');
            }else{
                alert('warning', sprintf(UPDATE_FAILED, COMMENT) . ' <a href="index.php?page=comments&post-id='.$post_id.'" data-page="comments" data-params="post-id='.$post_id.'">' . RETURN_TO_OVERVIEW . '</a>');
            }

        }
        return $result;
    }
}
?>