<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    /**
     * Show the import form.
     */
    public function showImportForm()
    {
        return view('members.import');
    }

    /**
     * Process the CSV file and import members.
     */
    public function importMembers(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        fgetcsv($handle);

        $count = 0;
        
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (empty(array_filter($data))) continue;

                $groupId = preg_replace('/[^0-9]/', '', $data[0]);

                User::firstOrCreate(
                    ['phone_number' => $data[5]], 
                    [
                        'group_id'     => $groupId,
                        'name'         => $data[1],
                        'county'       => $data[2],
                        'sub_county'   => $data[3],
                        'ward'         => $data[4],
                        'email'        => 'member_' . $data[5] . '_' . time() . rand(100,999) . '@northernhope.org',
                        'password'     => Hash::make('password123'),
                        'role'         => 'member',
                    ]
                );
                
                $count++;
            }
            fclose($handle);

            return redirect()->back()->with('success', "Success! Processed $count records.");
            
        } catch (\Exception $e) {
            if (is_resource($handle)) fclose($handle);
            
            Log::error('Import Error: ' . $e->getMessage());
            
            return redirect()->back()->withErrors(['error' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Register a single member via dashboard.
     */
    public function storeMember(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'group_id'   => 'required|exists:groups,id',
            'sub_county' => 'required|string|max:255',
            'county'     => 'required|string|max:255',
        ]);

        User::create([
            'name'       => $validated['name'],
            'group_id'   => $validated['group_id'],
            'sub_county' => $validated['sub_county'],
            'county'     => $validated['county'],
            'role'       => 'member',
            'password'   => Hash::make('password123'),
            // Email is auto-generated to prevent SQL 1364 errors
            'email'      => strtolower(str_replace(' ', '', $validated['name'])) . '_' . time() . '@northernhope.org',
        ]);

        return redirect()->back()->with('success', 'New member ' . $validated['name'] . ' added successfully.');
    }

    /**
     * Remove the specified member from the system permanently.
     */
    public function destroy($id)
    {
        // Locate the user/member
        $member = User::findOrFail($id);

        // Perform permanent deletion
        $member->forceDelete();

        return redirect()->back()->with('success', 'Member ' . $member->name . ' has been permanently removed.');
    }
}