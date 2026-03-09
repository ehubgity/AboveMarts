<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiveAway extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'give_aways';
    protected $casts = [
        'lucky_numbers' => 'array',
        'lucky_numbers_confirm' => 'array',
        'all_numbers' => 'array',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function questions(){
    	return $this->hasMany(Question::class);
    }
    public function all_questions(){
    	return $this->hasMany(Question::class,'test_id','id');
    }
    public function my_questions() {        
    	return $this->hasMany(Question::class);
    }

   

    public function storeTest($data){
    	return GiveAway::create($data);
    }

    public function allTest(){
    	return GiveAway::all();
    }

    public function editTest($id){
        return GiveAway::find($id);
    }

    public function updateTest($id,$data){
        return GiveAway::find($id)->update($data);
       
    }
    public function deleteTest($id){
        $test = GiveAway::find($id);
        $questions = Question::where('test_id',$test->id)->get();
        foreach($questions as $question) {
            $question->delete();
        }
        return GiveAway::find($id)->delete();

    }

    public function assignExam($data){
        foreach($data['test_id'] as $testId) {
            $test = GiveAway::find($testId);
            foreach($data['user_id'] as $userId) {
                $test->users()->syncWithoutDetaching($userId);
            }
        }
        return('good');
        // dd($data);
        // $TestId = $data['test_id'];
        // $Test = GiveAway::find($TestId);
        // $userId = $data['user_id'];
        // return $Test->users()->syncWithoutDetaching($userId);
    }

    public function hasTestAttempted(){
        $attemptTest  = [];
        $authUser = auth()->user()->id;
        $user = Result::where('user_id',$authUser)->get();
        foreach($user as $u){
            array_push($attemptTest,$u->test_id);
        }
        
        return $attemptTest;
    }

}
