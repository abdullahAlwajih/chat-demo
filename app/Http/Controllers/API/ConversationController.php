<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Musonza\Chat\Exceptions\DirectMessagingExistsException;
use Musonza\Chat\Models\Conversation;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $query = Conversation::all();
        return $this->responseFormat(200, __('Data fetched successfully'), $query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $user = $request->user();
        $another = User::find($request->input('id'));
        $participants = [$user, $another];
        try {
            $conversation = Chat::makeDirect()->createConversation($participants);
        } catch (DirectMessagingExistsException $e) {
            $conversation = Chat::conversations()->between($participants[0], $participants[1]);
        }
        return $this->responseFormat(200, __('Conversation created successfully'), $conversation);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $conversation = Chat::conversations()->getById($id);
        return $this->responseFormat(200, __("Data fetched successfully"), $conversation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        $conversation = Chat::conversations()->getById($id);
        $title = $request->input('title');
        $description = $request->input('description');
        $data = ['title' => $title, 'description' => $description];
        $conversation->update(['data' => $data]);
        return $this->responseFormat(200, __('Data has been updated successfully'), $conversation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function indexMessage(Request $request): Response
    {
        $conversation = Chat::conversations()->getById(1);
        $messages = $conversation->messages()->get();
        return $this->responseFormat(200, __('Data fetched successfully'), $messages);
    }

    public function sendMessage(Request $request): Response
    {
        $message = $request->input('message');
        $conversation = Chat::conversations()->getById($request->input('conversation_id'));
        $message = Chat::message($message)
            ->from($request->user())
            ->to($conversation)
            ->send();
        return $this->responseFormat(200, __('Messages have been sent successfully'), $message);
    }

    public function showMessage(int $id): Response
    {
        $messages = Chat::messages()->getById($id);
        return $this->responseFormat(200, __("Data fetched successfully"), $messages);
    }
}
