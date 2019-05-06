<div class="col-sm-3 tickets_chat">
    <div class="t_header"><strong>{{ __('admin.tickets.messages') }}</strong></div>
    <div class="t_messages">
        <div class="t_not-select">Select Ticket</div>
        <div class="t_flex-message-container"></div>
    </div>
    <div class="t_typing"><span id="typingName"></span> is typing ...</div>
    <div class="t_toolbar">
        <div class="text-container">
            <textarea name="message" id="message"></textarea>
            <div class="send-button">
                <div>
                    <i class="ti-control-play"></i>
                </div>
            </div>
        </div>
        <div class="t_tools">
            <div class="tool_col status_col">
                <select id="status">
                    @foreach(\App\Ticket::STATUSES as $key => $status)
                        <option value="{{ $status }}" data-key="{{ $key }}">{{ __('admin.tickets.statuses.' . $key) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="tool_col rooms_col">
                <select id="rooms"></select>
            </div>
        </div>
    </div>
</div>