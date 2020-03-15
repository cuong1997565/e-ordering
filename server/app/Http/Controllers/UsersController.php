<?php

namespace App\Http\Controllers;

use App\App\AuthorizationRepository;
use App\Jobs\SendMail;
use App\Models\Customer;
use App\Models\Error;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    private function validation()
    {
        $this->validate($this->request,
            [
                'username' => 'required|username_format',
                'password' => 'required|no_space|min:8|max:16',
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'group' => 'number'
            ]);
    }

    public function index()
    {
        if(User::isAdmin()) {
            $users = User::getDynamic();
            $this->output(['data' => $users], 200);
        } else if(User::isSaleFactory()) {
            $users = User::where('id',$this->curUser->id)->getDynamic();
            $this->output(['data' => $users], 200);
        }
    }

    public function getForm()
    {
        $user = User::where([['id', $this->data['id']]])->first();

        if ($user) {
            $this->output(['data' => $user], 200);
        } else {
            $this->output([MESSAGE => trans('User does not exist')], 404);
        }
    }

    public function saveForm()
    {
        if (isset($this->data['id'])) {
            $authorization = new AuthorizationRepository($this->request);
            $curUserId = $this->curUser->id;
            $permission = Permission::PERMISSION_EDIT_ADMIN()->id;
            if (!$authorization->userHasPermissionToDistributor($permission)) {
                $error = Error::NewPermissionError($curUserId, $permission);
                response()->json($error, StatusForbidden)->send(); die;
            }
        } else {
            $authorization = new AuthorizationRepository($this->request);
            $curUserId = $this->curUser->id;
            $permission = Permission::PERMISSION_CREATE_ADMIN()->id;
            if (!$authorization->userHasPermissionToDistributor($permission)) {
                $error = Error::NewPermissionError($curUserId, $permission);
                response()->json($error, StatusForbidden)->send(); die;
            }
        }
        if ($this->data['group'] == GROUP_ADMIN || $this->data['group'] == null) {
            //---------------------validate admin---------------------------
            if (isset($this->data['id'])) {
                // Update
                if ($this->data['password'] != null || $this->data['password_confirmation'] != null) {

                    $this->validate($this->request,
                        [
                            'name' => 'required|min:3|max:255',
                            'email' => 'required|sometimes|nullable|email|unique:users,email,' . $this->data['id'],
                            'group' => 'required|in:1,3,4',
                            'phone_number' => 'numeric|max:11111111111',
                            'password' => 'no_space|min:8|max:255',
                            'password_confirmation' => 'no_space|min:8|max:255|same:password',
                        ],
                        [
                            'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                            'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                            'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                            'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                            'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                            'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                            'group.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                            'phone_number.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                            'phone_number.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                            'phone_number.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                            'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                            'password.min' => trans('messages.api.min.app_error', ['Name' => 'password']),
                            'password.max' => trans('messages.api.max.app_error', ['Name' => 'password']),
                            'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                            'password_confirmation.max' => trans('messages.api.max.app_error', ['Name' => 'password confirmation']),
                            'password_confirmation.min' => trans('messages.api.min.app_error', ['Name' => 'password confirmation']),
                            'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
                        ]);

                } else {

                    $this->validate($this->request,
                        [
                            'name' => 'required|min:3|max:255',
                            'email' => 'required|sometimes|nullable|email|unique:users,email,' . $this->data['id'],
                            'group' => 'required|in:1,3,4',
                            'phone_number' => 'numeric|max:11111111111',
                        ],
                        [
                            'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                            'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                            'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                            'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                            'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                            'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                            'group.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                            'phone_number.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                            'phone_number.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                            'phone_number.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                        ]);

                }

                if ($this->data['password'] && !$this->data['password_confirmation']) {
                    $this->output(['password_confirmation' => [trans('The password confirmation field is required.')]], 422);
                    die;
                }
                if (!$this->data['password'] && $this->data['password_confirmation']) {
                    $this->output(['password' => [trans('The password field is required.')]], 422);
                    die;
                }
                if (!empty($this->data['password'])) {
                    $this->data['password'] = Hash::make($this->data['password']);
                } else {
                    unset($this->data['password']);
                }
            } else {
                // Create new
                $this->validate($this->request,
                    [
                        'name' => 'required|min:3|max:255',
                        'username' => 'required|no_space|min:3|max:255|is_ascii|unique:users,username',
                        'password' => 'required|no_space|min:8|max:255',
                        'password_confirmation' => 'required|no_space|min:8|max:255|same:password',
                        'email' => 'required|sometimes|nullable|email|unique:users,email',
                        'group' => 'required|in:1,3',
                        'phone_number' => 'required|numeric|max:11111111111',

                    ],
                    [
                        'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                        'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                        'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                        'username.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'username']),
                        'username.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'username']),
                        'username.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'username']),
                        'username.unique' => trans('messages.api.exist.app_error', ['Name' => 'username']),
                        'username.min' => trans('messages.api.min.app_error', ['Name' => 'username']),
                        'username.max' => trans('messages.api.max.app_error', ['Name' => 'username']),
                        'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                        'password.min' => trans('messages.api.min.app_error', ['Name' => 'password']),
                        'password.max' => trans('messages.api.max.app_error', ['Name' => 'password']),
                        'password_confirmation.min' => trans('messages.api.min.app_error', ['Name' => 'password confirmation']),
                        'password_confirmation.max' => trans('messages.api.max.app_error', ['Name' => 'password confirmation']),
                        'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                        'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                        'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                        'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                        'group.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                        'phone_number.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                        'phone_number.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                        'phone_number.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                        'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
                    ]);
                if ($this->data['password'] && !$this->data['password_confirmation']) {
                    $this->output(['password_confirmation' => [trans('The password confirmation field is required.')]], 422);
                    die;
                }
                $this->data['password'] = Hash::make($this->data['password']);

            }
        } elseif ($this->data['group'] == GROUP_SALE_FACTORY) {
            if (isset($this->data['id'])) {
                // Update

                if ($this->data['password'] != null || $this->data['password_confirmation'] != null) {
                    $this->validate($this->request,
                        [
                            'name' => 'required|min:3|max:255',
                            'email' => 'required|sometimes|nullable|email|unique:users,email,' . $this->data['id'],
                            'group' => 'required|in:1,3,4',
                            'phone_number' => 'required|numeric|max:11111111111',
                            'password' => 'no_space|min:8|max:255',
                            'password_confirmation' => 'no_space|min:8|max:255|same:password',
                            'factory_id' => 'required',
                        ],
                        [
                            'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                            'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                            'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                            'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                            'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                            'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                            'group.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                            'phone_number.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                            'phone_number.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                            'phone_number.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                            'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                            'password.min' => trans('messages.api.min.app_error', ['Name' => 'password']),
                            'password.max' => trans('messages.api.max.app_error', ['Name' => 'password']),
                            'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                            'password_confirmation.max' => trans('messages.api.max.app_error', ['Name' => 'password confirmation']),
                            'password_confirmation.min' => trans('messages.api.min.app_error', ['Name' => 'password confirmation']),
                            'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
                            'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory']),
                        ]);

                } else {

                    $this->validate($this->request,
                        [
                            'factory_id' => 'required',
                            'name' => 'required|min:3|max:255',
                            'email' => 'required|sometimes|nullable|email|unique:users,email,' . $this->data['id'],
                            'group' => 'required|in:1,3,4',
                            'phone_number' => 'required|numeric|max:11111111111',

                        ],
                        [
                            'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                            'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                            'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                            'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory']),
                            'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                            'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                            'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                            'group.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                            'phone_number.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                            'phone_number.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                            'phone_number.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                        ]);
                }

                if ($this->data['password'] && !$this->data['password_confirmation']) {
                    $this->output(['password_confirmation' => [trans('The password confirmation field is required.')]], 422);
                    die;
                }
                if (!$this->data['password'] && $this->data['password_confirmation']) {
                    $this->output(['password' => [trans('The password field is required.')]], 422);
                    die;
                }
                if (!empty($this->data['password'])) {
                    $this->data['password'] = Hash::make($this->data['password']);
                } else {
                    unset($this->data['password']);
                }
            } else {
                // Create new
                $this->validate($this->request,
                    [
                        'factory_id' => 'required',
                        'name' => 'required|min:3|max:255',
                        'username' => 'required|no_space|min:3|max:255|is_ascii|unique:users,username',
                        'password' => 'required|no_space|min:8|max:255',
                        'password_confirmation' => 'required|no_space|min:6|max:255|same:password',
                        'email' => 'required|sometimes|nullable|email|unique:users,email',
                        'group' => 'required|in:1,3',
                        'phone_number' => 'required|numeric|max:11111111111',

                    ],
                    [
                        'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                        'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                        'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                        'username.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'username']),
                        'username.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'username']),
                        'username.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'username']),
                        'username.min' => trans('messages.api.min.app_error', ['Name' => 'username']),
                        'username.max' => trans('messages.api.max.app_error', ['Name' => 'username']),
                        'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                        'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                        'password.min' => trans('messages.api.min.app_error', ['Name' => 'password']),
                        'password.max' => trans('messages.api.max.app_error', ['Name' => 'password']),
                        'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
                        'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                        'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                        'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                        'username.unique' => trans('messages.api.exist.app_error', ['Name' => 'username']),
                        'group.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                        'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory']),
                        'phone.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                        'phone_number.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                        'phone_number.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                    ]);
                if ($this->data['password'] && !$this->data['password_confirmation']) {
                    $this->output(['password_confirmation' => [trans('The password confirmation field is required.')]], 422);
                    die;
                }
                $this->data['password'] = Hash::make($this->data['password']);
            }
        }
        $this->saveRecord('User', ACTIVE_TRUE);
    }

    public function detail()
    {
        $user = User::with('factory')->where('id', $this->data['id'])->first();
        if ($user) {
            $this->output(['data' => $user], 200);
        } else {
            $this->output([MESSAGE => trans('User does not exist')], 404);
        }
    }

    public function delete()
    {
        $this->deleteRecord('User');
    }

    public function register()
    {
        $this->validation();

        $this->data['password'] = Hash::make($this->data['password']);
        $this->data['active'] = 0;
        $this->data['extra_token'] = mt_rand(1111111111, 9999999999) . time() . mt_rand(1111111111, 9999999999);

        $user = User::create($this->data);
        if ($user) {
            /* ---------- Send activation mail { ---------- */

            $activationLink = URL::to('/api/users/activation/' . $this->data['extra_token']);

            dispatch(new SendMail
            (
                EMAIL_TEMPLATE_SIGNUP,
                [
                    'to' => $this->data['email'],
                    'subject' => trans('Member signup confirmation')
                ],
                [
                    'name' => $this->data['name'],
                    'email' => $this->data['email'],
                    'href' => $activationLink
                ]
            ));
            /* ---------- Send activation mail } ---------- */

            $this->output([MESSAGE => trans('New user has been registered')], 200);
        }
    }

    public function resetPassword()
    {
        $this->validate($this->request,
            [
                'email' => 'required|email'
            ]);

        $user = User::where('email', $this->data['email'])->first();

        if ($user) {
            if ($user->active == ACTIVE_FALSE) {
                $this->output([MESSAGE => trans('Cannot reset password, let active account first')], 400);
            } else {
                $this->data['extra_token'] = mt_rand(1111111111, 9999999999) . time() . mt_rand(1111111111, 9999999999);
                if ($user->fill($this->data)->save()) {
                    $activationLink = URL::to('/page/forgot-password/' . $this->data['extra_token']);

                    dispatch(new SendMail
                    (
                        EMAIL_TEMPLATE_FORGOT_PASSWORD,
                        [
                            'to' => $this->data['email'],
                            'subject' => trans('Reset password confirmation')
                        ],
                        [
                            'name' => $user->name,
                            'email' => $this->data['email'],
                            'href' => $activationLink
                        ]
                    ));
                    /* ---------- Send activation mail } ---------- */
                }
                $this->output([MESSAGE => trans('We have sent link reset password to your email')], 200);
            }
        } else {
            $this->output([MESSAGE => trans('We have sent link reset password to your email')], 200);
        }
    }


    public function resetPasswordActivation()
    {
        $this->validate($this->request,
            [
                'token' => 'required',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required|min:8'
            ]);

        $token = $this->request['token'];
        $time_token = substr($token, 10, -10);

        $expire_token = $this->getDiffInMinutes($time_token);

        if ($expire_token > 30) {
            $this->output([MESSAGE => trans('Reset password token expired')], 400);
        }

        $user = User::where(['extra_token' => $this->request['token']])->first();
        $new_password = Hash::make($this->data['password']);

        if ($user) {
            $updateData =
                [
                    'extra_token' => null,
                    'password' => $new_password,
                ];

            $user = User::where(['id' => $user->id])->update($updateData);
            if ($user) {
                $this->output([MESSAGE => trans('Reset password successfully')], 200);
            } else {
                $this->output([MESSAGE => trans('Cannot reset password')], 400);
            }
        } else {
            $this->output([MESSAGE => trans('Invalid activation link')], 400);
        }
    }

    /**
     *  Get the diff in minute
     */
    protected function getDiffInMinutes($time)
    {
        $timetoDiff = Carbon::parse(date('Y/m/d H:i:s', $time));
        $now = Carbon::parse(date('Y/m/d H:i:s', time()));
        return $now->diffInMinutes($timetoDiff);
    }

    public function activation()
    {
        $this->validate($this->request,
            [
                'token' => 'required',
            ]);

        $user = User::where(['extra_token' => $this->data['token']])->first();
        if ($user) {
            $updateData =
                [
                    'active' => ACTIVE_TRUE,
                    'auth_token' => 'new_auth_token',
                    'extra_token' => null
                ];

            $user = User::where(['id' => $user->id])->update($updateData);
            if ($user) {
                $this->output(['token' => $updateData['auth_token']], 200);
            } else {
                $this->output([MESSAGE => trans('Cannot active account')], 400);
            }
        } else {
            $this->output([MESSAGE => trans('Invalid activation link')], 400);
        }
    }

    public function login()
    {
        $this->validate($this->request,
            [
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'username.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'username']),
                'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
            ]);
        $user = User::where(['username' => $this->data['username']])->first();
        if ($user && Hash::check($this->data['password'], $user->password)) {
            if ($user->active == ACTIVE_TRUE) {
                $token = $user->getAuthToken();
                $data = $user->toArray();

                if ($token == '') {
                    $data['auth_token'] = sha1('[' . $user->id . '-' . date('Y-m-d H:i:s') . ']');
                    $token = $data['auth_token'];
                }

                // Update last action
                Cache::store('file')->put($token, 'online', SESSION_EXPIRED);
                User::where(['id' => $user->id])->update($data);

                $this->output(['token' => $token, 'profile' => $user->toArray()], 200);
            } else {
                $this->output(['error-message' => trans('User is blocked')], 422);
            }
        } else {
            $this->output(['error-message' => trans('Invalid username or password')], 422);
        }
    }

    /**
     * @SWG\Get(
     *   path="/users/logout", tags={"user"},
     *   summary="Member login from the system",
     *   description="Member logout from the system",
     *   operationId="logout", produces={"application/json"},
     *   @SWG\Parameter( name="token", in="path", description="The login token", required=true, type="string" ),
     *   @SWG\Response ( response=200, description="Success" ),
     *   @SWG\Response ( response=400, description="Error" )
     * )
     */
    public function logout()
    {
        if ($this->curUser) {
            User::where(['id' => $this->curUser->id])->update(['auth_token' => null]);
            $this->output([MESSAGE => trans('User is logout')], 200);
        } else {
            $this->output([MESSAGE => trans('Invalid request')], 400);
        }
    }

    public function checkToken()
    {
        $this->output(['valid' => 1], 200);
    }

    public function password()
    {
        $this->validate($this->request,
            [
                'old_password' => 'required|min:8|max:16',
                'new_password' => 'required|min:8|max:16',
                'confirm_password' => 'required|min:8|max:16',
            ]);

        if ($this->data['new_password'] != $this->data['confirm_password']) {
            $this->output(['confirm_password' => [trans('The confirm password does not match')]], 422);
            die;
        }

        $user = User::where(['id' => $this->curUser->id])->first();
        if ($user && Hash::check($this->data['old_password'], $user->password)) {
            User::where(['id' => $user->id])->update(['password' => Hash::make($this->data['new_password'])]);
            $this->output([MESSAGE => trans('Password has been changed successfully')], 200);
        } else {
            $this->output(['old_password' => [trans('Old password is invalid')]], 422);
        }
    }

    public function getProfile()
    {
        $this->output(['data' => $this->curUser], 200);
    }

    /** Update Profile */
    public function updateProfile()
    {
        $fileImages = $this->request->file('avatar');
        if ($this->curUser->group == GROUP_ADMIN || $this->curUser->group == GROUP_SALE_FACTORY) {
            if ($this->data['password'] != null || $this->data['password_confirmation'] != null) {

                $this->validate($this->request,
                    [
                        'name' => 'required|min:3|max:255',
                        'group' => 'required|in:1,3,4',
                        'phone_number' => 'required|numeric|max:11111111111',
                        'password' => 'no_space|min:8|max:255',
                        'password_confirmation' => 'no_space|min:8|max:255|same:password',
                    ],
                    [
                        'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                        'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                        'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                        'group.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                        'phone_number.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                        'phone_number.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                        'phone_number.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                        'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                        'password.min' => trans('messages.api.min.app_error', ['Name' => 'password']),
                        'password.max' => trans('messages.api.max.app_error', ['Name' => 'password']),
                        'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                        'password_confirmation.max' => trans('messages.api.max.app_error', ['Name' => 'password confirmation']),
                        'password_confirmation.min' => trans('messages.api.min.app_error', ['Name' => 'password confirmation']),
                        'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
                    ]);

            } else {

                $this->validate($this->request,
                    [
                        'name' => 'required|min:3|max:255',
                        'group' => 'required|in:1,3,4',
                        'phone_number' => 'required|numeric|max:11111111111',
                    ],
                    [
                        'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                        'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                        'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                        'group.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                        'phone_number.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                        'phone_number.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                        'phone_number.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                    ]);

            }
            if ($this->data['password'] && !$this->data['password_confirmation']) {
                $this->output(['password_confirmation' => [trans('The password confirmation field is required.')]], 422);
                die;
            }
            if (!$this->data['password'] && $this->data['password_confirmation']) {
                $this->output(['password' => [trans('The password field is required.')]], 422);
                die;
            }
            $user = User::where(['id' => $this->curUser->id])->with('role')->first();
            //xu ly permission
            $object = new \stdClass();
            foreach ($user['role']['permissions'] as $key => $value)
            {
                $object->$value = $value;
            }

            $user['role']['permissions'] = $object;
            if (!empty($this->data['password'])) {
                $this->data['password'] = Hash::make($this->data['password']);
            } else {
                unset($this->data['password']);
            }

            if ($user && $user->fill($this->data)->save()) {
                $this->output([MESSAGE => trans('Profile has been changed successfully'), 'profile' => $user], 200);
            } else {
                $this->output([MESSAGE => trans('Profile cannot be updated')], 400);
            }
        }

    }

    public function check()
    {
        $check = Customer::where('extra_token', $this->data['extra_token'])->getDynamic();
        $this->output($check, 200);
    }
}
