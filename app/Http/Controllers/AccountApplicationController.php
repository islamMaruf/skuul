<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountApplicationStatusChangeRequest;
use App\Http\Requests\UpdateAccountApplicationRequest;
use App\Models\User;
use App\Services\AccountApplication\AccountApplicationService;
use App\Services\User\UserService;

class AccountApplicationController extends Controller
{
    /**
     * Contains account service instance.
     *
     * @var AccountApplicationService
     */
    public AccountApplicationService $accountApplicationService;

    /**
     * User service instance.
     *
     * @var UserService
     */
    public UserService $userService;

    public function __construct(AccountApplicationService $accountApplicationService, UserService $userService)
    {
        $this->accountApplicationService = $accountApplicationService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'applicant']);

        return view('pages.account-application.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $applicant
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $applicant)
    {
        $this->userService->verifyUserIsOfRoleElseNotFound($applicant, 'applicant');
        $this->authorize('view', [$applicant, 'applicant']);
        $data['applicant'] = $applicant;

        return view('pages.account-application.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $applicant
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $applicant)
    {
        $this->userService->verifyUserIsOfRoleElseNotFound($applicant, 'applicant');
        $this->authorize('update', [$applicant, 'applicant']);
        $data['applicant'] = $applicant;

        return view('pages.account-application.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateAccountApplicationRequest $request
     * @param \App\Models\User                                   $applicant
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountApplicationRequest $request, User $applicant)
    {
        $this->userService->verifyUserIsOfRoleElseNotFound($applicant, 'applicant');
        $this->authorize('update', [$applicant, 'applicant']);
        $data = $request->except('_method', '_token');
        $this->accountApplicationService->updateAccountApplication($applicant, $data);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $applicant
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $applicant)
    {
        $this->userService->verifyUserIsOfRoleElseNotFound($applicant, 'applicant');
        $this->authorize('delete', [$applicant, 'applicant']);

        $this->accountApplicationService->deleteAccountApplicant($applicant);

        return back();
    }

    /**
     * View for changing application status.
     *
     * @return void
     */
    public function changeStatusView(User $applicant)
    {
        $data['applicant'] = $applicant;

        return view('pages.account-application.change-status', $data);
    }

    /**
     * Change Application Statis.
     *
     * @param User    $applicant
     * @param Request $request
     *
     * @return void
     */
    public function changeStatus(User $applicant, AccountApplicationStatusChangeRequest $request)
    {
        $data = $request->validated();
        $this->accountApplicationService->changeStatus($applicant, $data);

        return back();
    }

    /**
     * View rejected applications.
     *
     * @return void
     */
    public function rejectedApplicationsView()
    {
        return view('pages.account-application.rejected-applications');
    }
}
