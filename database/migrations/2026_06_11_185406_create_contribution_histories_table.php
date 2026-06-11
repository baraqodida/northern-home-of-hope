public function up()
{
    Schema::create('contribution_histories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('contribution_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 12, 2);
        $table->string('week_label'); // e.g., "Week 24"
        $table->timestamps();
    });
}