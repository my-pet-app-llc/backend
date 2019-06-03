<?php

return [
    'greeting' => 'Welcome <br/> to <br/> MyPet Admin Panel!',
    'title' => [
        'mypet_admin_panel'  => 'MyPet Admin Panel',
    ],
    'side_bar' => [
        'support_tickets'   => 'Support Tickets',
        'users'             => 'Users',
        'app_updates'       => 'App Updates',
        'partner_materials' => 'Partner Materials',
    ],
    'auth' => [
        'mypet_admin'  => 'MyPet Admin',
        'username'     => 'Username',
        'password'     => 'Password',
        'remember_me'  => 'Remember Me',
        'login'        => 'Login',
        'welcome_back' => 'Welcome back! Pleace login to your account.'
    ],
    'buttons' => [
        'remove'       => 'Remove',
        'add_update'   => 'Add Update',
        'add_material' => 'Add Material',
        'cancel'       => 'Cancel',
        'delete'       => 'Delete',
        'email'        => 'Email',
        'ban'          => 'Ban',
        'un_ban'       => 'Unban',
        'suspend'      => 'Suspend to 72 hours',
        'un_suspend'      => 'Unsuspend',
        'confirm'      => 'Confirm',
    ],

    'users' => [
        'username'    => 'User Name',
        'user_e-mail' => 'User E-mail',
        'data_joined' => 'Date Joined',
        'age'         => 'Age',
        'location'    => 'Location',
        'status'      => 'Status',
        'uploaded_images' => 'Uploaded Images',
        'state' => [
            'in_progress' => 'Support Ticket in Progress',
            'reported'    => 'REPORTED',
            'reporting'   => 'REPORTED/Support Ticket',
            'banned'      => 'Banned',
            'suspended'   => 'Suspended',
            'normal'      => 'Normal',
        ], 
    ],

    'tickets' => [
        'date'     => 'Date',
        'time'     => 'Time',
        'ticket'   => 'Ticket #',
        'status'   => 'Ticket Status',
        'messages' => 'Message Thread',
        'statuses' => [
            'new' => 'New',
            'reported_user' => 'REPORTED USER',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
        ]
    ],

    'updates' => [
        'update_title'   => 'Update Title:',
        'date_added'     => 'Date Added',
        'add_new_update' => 'Add New Update:',
        'update_image'   => 'Update Image:',
        'update_text'    => 'Update Text:',
    ],

    'materials' => [
        'material_title'       => 'Material Title:',
        'date_added'           => 'Date Added',
        'add_new_promotion'    => 'Add New Promotion:',
        'promotion_image'      => 'Promotion Image:',
        'promotion_short_text' => 'Promotion Short Text:',
        'promotion_full_text'  => 'Promotion Full Text:',
        'promotion_address'    => 'Promotion Street Address:',
        'promotion_phone'      => 'Promotion Phone Number:',
        'promotion_website'    => 'Promotion Website:',
    ],

    'messages' => [
        'update_save'         => 'Update successfully added!',
        'material_save'       => 'Promotion successfully added!',
        'user_ban'            => 'User was banned!',
        'user_un_ban'         => 'User was unbanned!',
        'user_suspend'         => 'User was suspended!',
        'user_un_suspend'         => 'User was unsuspended!',
        'update_warning'      => 'Are you sure you want to delete the update? <br/> This cannot be undone!',
        'material_warning'    => 'Are you sure you want to delete the material? <br/> This cannot be undone!',
        'update_remove'       => 'Update deleted successfully!',
        'material_remove'     => 'Material deleted successfully!',
        'confirm_user_ban'    => 'Are you sure you want to ban the user?',
        'confirm_user_un_ban' => 'Are you sure you want to unbanned the user?',
        'confirm_user_suspend' => 'Are you sure you want to suspend the user?',
        'confirm_user_un_suspend' => 'Are you sure you want to unsuspend the user?',
    ]
];