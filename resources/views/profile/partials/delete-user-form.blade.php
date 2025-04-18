<form method="POST" action="{{ route('profile.destroy') }}">
    @csrf
    @method('DELETE')

    <div class="card">
        <div class="card-header" id="delete_account">
            <h3 class="card-title">
                Delete Account
            </h3>
        </div>
        <div class="card-body flex flex-col lg:py-7.5 lg:gap-7.5 gap-3">
            <div class="flex flex-col gap-5">
                <div class="text-2sm text-gray-800">
                    We regret to see you leave. Confirm account deletion below. Your data will be permanently removed. Thank you for being part of our
                    community. Please check our
                    <a class="link" href="#">
                        Setup Guidelines
                    </a>
                    if you still wish continue.
                </div>
                <label class="checkbox-group" >
                    <input id="confirm_delete" class="checkbox checkbox-sm" name="confirm_delete" type="checkbox" value="1" :messages="$errors->get('confirm_delete')">
                    <span class="checkbox-label">
                    Confirm deleting account
                   </span>
                </label>
            </div>
            <div class="flex justify-end gap-2.5">

                <button type="submit" name="action" value="deactivate" class="btn btn-light">
                    Deactivate Instead
                </button>

                <button type="submit" name="action" value="delete" class="btn btn-danger">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</form>
