<?php

namespace LittleThings;

class JsonPostRepository implements PostRepository, JsonRepository {

    protected $storagePath;
    protected $postCollection;

    function __construct($storagePath) {
        if(!is_string($storagePath)) {
            throw new Exception("Constructor takes in path as string.");
        }

        if(!file_exists($storagePath)) {
            throw new Exception("File does not exist.");
        }

        $this->storagePath = $storagePath;

        $posts = $this->readJson();
        $this->postCollection = new PostCollection($posts);
    }


    /**
     * Creates array of posts from associative array
     *
     * @param array $posts
     * @return array
     **/
    protected function hydrate(array $posts)
    {
        return array_map(function ($post) {
            return new Post(
                $post['id'],
                $post['date'],
                $post['authorId'],
                $post['title'],
                $post['slug']
            );
        }, $posts);
    }



    /* JsonRepository interface method implementation */

    /**
     * Reads Json file and returns array
     *
     * @return array
     **/
    public function readJson() {
        $json = json_decode(file_get_contents($this->storagePath), true);
        return $this->hydrate($json);
    }

    /**
     * Writes data back to Json file
     *
     * @param array $data
     * @return void
     **/
    public function writeJson(array $data) {
        file_put_contents($this->storagePath, json_encode($data));
    }


    /* PostRepository interface method implementation */

    /**
     * Return collection of all posts
     *
     * @return PostCollection
     */
    public function all() {
        return $this->postCollection;
    }

    /**
     * Add new post to repository
     *
     * @param Post $post
     * @return boolean
     */
    public function add(Post $post) {
        $this->postCollection->append($post);
        $json = $this->postCollection->jsonSerialize();
        $this->writeJson($json);
    }

    /**
     * Find post by specific ID
     *
     * @param integer $id
     * @return Post
     */
    public function findById($id) {
        // if findById is used a lot
        // would either use a assoc array $postId => $postObject for quicker lookups if IDs are unique (more memory)
        // or keep a sorted array in PostCollection and implement binary search (slower inserts)

        foreach($this->postCollection as $post) {
            if($post->id == $id) {
                return $post;
            }
        }
        return null;
    }

}