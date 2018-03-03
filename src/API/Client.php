<?php
// Fetlife API for PHP
//  Written By Jeremy Harmon
//  http://github.com/zordtk/fetlife-php
//
// Permission is hereby granted, free of charge, to any person obtaining
// a copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to
// permit persons to whom the Software is furnished to do so, subject to
// the following conditions:
//
// The above copyright notice and this permission notice shall be
// included in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
// EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
// NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
// LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
// OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

namespace Zordtk\Fetlife\API;

define('PARAM_SORT_ORDER_UPDATED_DESC',         '-updated_at');
define('PARAM_VALUE_FRIEND_REQUEST_SENT',       'sent');
define('PARAM_VALUE_FRIEND_REQUEST_RECEIVED',   'received');
define('PARAM_VALUE_EVENT_RSVP_YES',            'yes');
define('PARAM_VALUE_EVENT_RSVP_MAYBE',          'maybe');

define('CLIENT_ID',                             'd8f8ebd522bf5123c3f29db3c8faf09029a032b44f0d1739d4325cd3ccf11570');
define('CLIENT_SECRET',                         '47273306a9a3a3448a908748eff13a21a477cc46f6a3968b5c7d05611c4f2f26');
define('MAX_PAGE_LIMIT',                        25);

class Client
{
    const API_BASE_URI          = "https://app.fetlife.com";
    const AUTH_HEADER_PREFIX    = 'Bearer ';

    private $mAccessToken       = null;
    private $mLogger            = null;
    private $mDecodeOnReturn    = true;

    public function __constructor($decodeJsonOnReturn = true)
    {
        $this->mDecodeOnReturn = $decodeOnReturn;
    }

    public function getMe()
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/me');
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberGroupMemberships($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/memberships", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getConversations($orderBy=PARAM_SORT_ORDER_UPDATED_DESC, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/me/conversations', ['query' => ['order_by' => $orderBy, 'limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getConversation($conversationId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/me/conversations/${conversationId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getFriends($limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/me/friends', ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMessages($conversationId, $sinceMessageId, $untilMessageId, $limit=MAX_PAGE_LIMIT)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/me/conversations/${conversationId}/messages",
                                ['query' => ['since_id' => $sinceMessageId, 'until_id' => $untilMessageId, 'limit' => $limit]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function searchMembers($query, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/search/members', ['query' => ['query' => $query, 'limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function searchGroups($query, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/search/groups', ['query' => ['query' => $query, 'limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getGroupMembers($groupId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/groups/${groupId}/memberships", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getGroupDiscussions($groupId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/groups/${groupId}/posts", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getGroupDiscussion($groupId, $groupPostId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/groups/${groupId}/posts/${groupPostId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getGroupMessages($groupId, $groupPostId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/groups/${groupId}/posts/${groupPostId}/comments", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function postGroupMessage($groupId, $groupPostId, $body)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->request('POST', self::API_BASE_URI . "/api/v2/groups/${groupId}/posts/${groupPostId}/comments", ['form_params' => ['body' => $body]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMember($memberId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getEvent($eventId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/events/${eventId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getGroup($groupId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/groups/${groupId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function joinGroup($groupId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->put(self::API_BASE_URI . "/api/v2/groups/${groupId}/join");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function leaveGroup($groupId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->delete(self::API_BASE_URI . "/api/v2/groups/${groupId}/join");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function followDiscussion($groupId, $groupPostId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->put(self::API_BASE_URI . "/api/v2/groups/${groupId}/posts/${groupPostId}/follow");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function unfollowDiscussion($groupId, $groupPostId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->delete(self::API_BASE_URI . "/api/v2/groups/${groupId}/posts/${groupPostId}/follow");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getRsvps($eventId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/me/rsvps', ['query' => ['event_id' => $eventId]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberFeed($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/latest_activity", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberRelationship($memberId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/relationships");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberPictures($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/pictures", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberVideos($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/videos", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberFriends($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/friends", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberFollowers($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/followers", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberFollowees($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/following", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberStatuses($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/statuses", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberRsvps($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/rsvps", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getWriting($memberId, $writingId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/writings/${writingId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getMemberWritings($memberId, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/members/${memberId}/writings", ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getEventRsvps($eventId, $status, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . "/api/v2/events/${eventId}/rsvps", ['query' => ['status' => $status, 'limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function postMessage($conversationId, $message)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->request('POST', self::API_BASE_URI . "/api/v2/me/conversations/${conversationId}/messages", ['form_params' => ['body' => $message]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function setMessagesRead($conversationId, $ids)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->put(self::API_BASE_URI . "/api/v2/me/conversations/${conversationId}/messages/read", ['form_params' => ['ids' => $ids]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function postConversation($userId, $subject, $message)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->request('POST', self::API_BASE_URI . "/api/v2/me/conversations", ['form_params' => ['user_id' => $userId, 'subject' => $subject, 'body' => $message]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getFriendRequests($filter, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/me/friendrequests', ['query' => ['filter' => $filter, 'limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function acceptFriendRequest($friendRequestId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->put(self::API_BASE_URI . "/api/v2/me/friendrequests/${friendRequestId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function cancelFriendRequest($friendRequestId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->delete(self::API_BASE_URI . "/api/v2/me/friendrequests/${friendRequestId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function createFriendRequest($friendId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->request('POST', self::API_BASE_URI . '/api/v2/me/friendrequests', ['form_params' => ['member_id' => $friendId]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function createFollow($memberId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->put(self::API_BASE_URI . "/api/v2/members/${memberId}/follow");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function removeFollow($memberId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->delete(self::API_BASE_URI . "/api/v2/members/${memberId}/follow");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function removeFriendship($memberId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->delete(self::API_BASE_URI . "/api/v2/me/relations/${memberId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getFeed($limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/me/feed', ['query' => ['limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getStuffYouLove($timeStamp=null, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/explore/stuff-you-love', ['query' => ['marker' => $timeStamp, 'limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getKinkyAndPopular($timeStamp=null, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/explore/fresh-and-pervy', ['query' => ['until' => $timeStamp, 'limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function putLove($contentType, $contentId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->put(self::API_BASE_URI . "/api/v2/me/loves/${contentType}/${contentId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function putRsvps($eventId, $rsvpsType)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/me/rsvps', ['query' => ['event_id' => $eventId, 'status' => $rsvpsType]]);
        $json   = json_decode($res->getBody()->getContents());
    }

    public function deleteLove($contentType, $contentId)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->delete(self::API_BASE_URI . "/api/v2/me/loves/${contentType}/${contentId}");
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function searchEventsByLocation($latitude, $longitude, $range, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/search/events/by_location', ['query' => ['latitude' => $latitude, 'longitude' => $longitude, 'range' => $range, 'limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function searchEvents($query, $limit=MAX_PAGE_LIMIT, $page=1)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/search/events', ['query' => ['query' => $query, 'limit' => $limit, 'page' => $page]]);
        return( $this->mDecodeOnReturn ? json_decode($res->getBody()->getContents()) : $res->getBody()->getContents() );
    }

    public function getAppId($idToObfuscate)
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => self::AUTH_HEADER_PREFIX . $this->mAccessToken]]);
        $res    = $client->get(self::API_BASE_URI . '/api/v2/ids', ['query' => ['id_to_obfuscate' => $idToObfuscate]]);
        return json_decode($res->getBody()->getContents())->id;
    }

    public function login($username, $password)
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                  => CLIENT_ID,
            'clientSecret'              => CLIENT_SECRET,
            'redirectUri'               => 'urn:ietf:wg:oauth:2.0:oob',
            'urlAuthorize'              => '',
            'urlAccessToken'            => self::API_BASE_URI . '/api/oauth/token',
            'urlResourceOwnerDetails'   => ''
        ]);

        try
        {
            $accessToken = $provider->getAccessToken('password', [
                'username' => $username,
                'password' => $password
            ]);

            $this->mAccessToken = $accessToken->getToken();
            return true;
        }
        catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e)
        {
            throw($e->getMessage());
            return false;
        }
    }
}

?>
